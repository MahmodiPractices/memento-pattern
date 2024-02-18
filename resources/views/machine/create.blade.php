@extends('layout.master')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/nouislider/nouislider.css') }}"/>
@endsection

@section('script')
    <script src="{{ asset('assets/vendor/libs/nouislider/nouislider.js') }}"></script>

    <script>
        function sliderFactory(ele, min, max, step = 1) {
            noUiSlider.create(ele, {
                start: [1],
                connect: true,
                behaviour: "tap-drag",
                step: step,
                tooltips: true,
                range: {
                    min: min,
                    max: max
                },
                pips: {
                    mode: "steps",
                    stepped: true,
                    density: 10
                }
            });
        }



        let sliders = [
            {
                ele : document.getElementById('core-count'),
                min : 1,
                max: 12,
                input: document.querySelector('input[name=core]')
            },
            {
                ele : document.getElementById('ram-capacity'),
                min : 2,
                max: 8,
                input: document.querySelector('input[name=ram]')
            },
            {
                ele : document.getElementById('storage-capacity'),
                min : 10,
                max: 100,
                step: 10,
                input: document.querySelector('input[name=storage]')
            }
        ]

        function setSliderInputValue(slider){
            slider.input.value = slider.ele.noUiSlider.get()
        }

        sliders.forEach(function(slider){
            sliderFactory(slider.ele, slider.min, slider.max, slider.step)

            setSliderInputValue(slider)

            slider.ele.noUiSlider.on('change', function(){
                setSliderInputValue(slider)
            })
        })

    </script>
@endsection

@section('content')
    <div class="col-12">
        <x-alert/>
        <div class="card mb-4">
            <h5 class="card-header heading-color mb-0 pb-0">ایجاد دستگاه جدید</h5>
            <div class="card-body">
                <form action="{{ route('machine.store') }}" method="post" class="row justify-content-end">
                    @csrf
                    <dib class="form-group col-12 col-md-6 px-5 py-4 my-1">
                        <div>
                            <label for="defaultFormControlInput" class="form-label">نام دستگاه جدید :</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="defaultFormControlInput" placeholder="برای مثال : سرور 24" aria-describedby="defaultFormControlHelp">
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                            <div class="valid-feedback"></div>
                        </div>
                    </dib>
                    <dib class="form-group col-12 col-md-6 px-5 py-4 my-1">
                        <input type="hidden" name="core">
                        <small class="text-light fw-semibold">تعداد هسته پردازشی :</small>
                        <div id="core-count"></div>
                    </dib>
                    <dib class="form-group col-12 col-md-6 px-5 py-4 my-1">
                        <input type="hidden" name="ram">
                        <small class="text-light fw-semibold">میزان حافظه موقت GB :</small>
                        <div id="ram-capacity"></div>
                    </dib>
                    <dib class="form-group col-12 col-md-6 px-5 py-4 my-1">
                        <input type="hidden" name="storage">
                        <small class="text-light fw-semibold">میزان حافظه دائمی GB :</small>
                        <div id="storage-capacity"></div>
                    </dib>
                    <div class="col-2 mt-5">
                        <button type="submit" class="btn rounded-pill me-2 btn-success">ایجاد</button>
                        <a href="{{ route('machine.index') }}" class="btn rounded-pill me-2 btn-warning">انصراف</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
