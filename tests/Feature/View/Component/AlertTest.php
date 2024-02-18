<?php

namespace Tests\Feature\View\Component;

use App\View\Components\Alert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AlertTest extends TestCase
{
    /**
     * Asserts that only flash sessions that has true key structure displays with component
     *
     * Example true key structure : 'alert-success', 'alert-error'
     *
     * @return void
     */
    public function test_component_display_only_flash_sessions_that_have_correct_alert_key_name_structure()
    {
        $alertFlashes = [
            'alert-info' => 'User went online !',
            'alert-error' => 'There was an error sending the message to the user !'
        ];

        $simpleFlashes = [
            'user-agent' => $this->faker->userAgent,
            'name' => $this->faker->name,

        ];

        $flashes = array_merge($alertFlashes, $simpleFlashes);

        foreach ($flashes as $key => $message)
            session()->flash($key, $message);

        $render = $this->component(Alert::class);

        foreach ($alertFlashes as $message)
            $render->assertSee($message);

        foreach ($simpleFlashes as $message)
            $render->assertDontSee($message);
    }

    /**
     * Asserts all passed alerts to view displays with true color and css class
     *
     * @return void
     */
    public function test_component_display_alerts_with_true_boostrap_class_and_color_style()
    {
        $levels = Alert::LEVELS;

        foreach ($levels as $level){
            session()->flash("alert-{$level}", $this->faker->text);

            $render = $this->component(Alert::class);

            $render->assertSee("alert-{$level}");

            session()->reflash();
        }
    }

    /**
     * Asserts displays all messages of nested alert flash value
     *
     * @return void
     */
    public function test_component_display_nested_alert_flash_messages()
    {
        $nestedKey = 'alert-success';

        $nestedMessages = [
            'New category added !',
            'New blog added !',
            'New Tag added !',
        ];

        $nonNestedKey = 'alert-error';

        $nonNestedMessage = 'something was wrong !';

        $alerts = [
            $nestedKey => $nestedMessages,
            $nonNestedKey => $nonNestedMessage
        ];

        foreach ($alerts as $key => $value)
            session()->flash($key, $value);

        $render = $this->component(Alert::class);

        foreach ($nestedMessages as $message)
            $render->assertSeeInOrder([$nestedKey, $message]);

        $render->assertSeeInOrder([$nonNestedKey, $nonNestedMessage]);
    }
}
