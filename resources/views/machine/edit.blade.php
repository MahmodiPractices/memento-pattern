@extends('layout.master')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/nouislider/nouislider.css') }}"/>
@endsection

@section('script')
    <script src="{{ asset('assets/vendor/libs/nouislider/nouislider.js') }}"></script>

    <script>
        function sliderFactory(ele, min = 1, max = 12, step = 1) {
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

        let coreCount = document.getElementById("core-count")
        let ramCapacity = document.getElementById("ram-capacity")
        let storageCapacity = document.getElementById("storage-capacity")

        sliderFactory(coreCount)
        sliderFactory(ramCapacity, 2, 8)
        sliderFactory(storageCapacity, 10, 100, 10)

        console.log(coreCount.noUiSlider.get())
    </script>
@endsection

@section('content')
    <div class="col-12">
        <div class="card mb-4">
            <h5 class="card-header heading-color mb-0 pb-0">
                ویرایش دستگاه
                <span>شماره ۱</span>
            </h5>
            <div class="card-body">
                <div class="row justify-content-center">
                    <dib class="form-group col-12 col-md-6 px-5 py-4 my-1">
                        <div>
                            <label for="defaultFormControlInput" class="form-label">نام دستگاه جدید :</label>
                            <input type="text" class="form-control" id="defaultFormControlInput" placeholder="برای مثال : سرور 24" aria-describedby="defaultFormControlHelp">
                            <div class="invalid-feedback"></div>
                            <div class="valid-feedback"></div>
                        </div>
                    </dib>
                    <dib class="form-group col-12 col-md-6 px-5 py-4 my-1">
                        <small class="text-light fw-semibold">تعداد هسته پردازشی :</small>
                        <div id="core-count"></div>
                    </dib>
                    <dib class="form-group col-12 col-md-6 px-5 py-4 my-1">
                        <small class="text-light fw-semibold">میزان حافظه موقت GB :</small>
                        <div id="ram-capacity"></div>
                    </dib>
                    <dib class="form-group col-12 col-md-6 px-5 py-4 my-1">
                        <small class="text-light fw-semibold">میزان حافظه دائمی GB :</small>
                        <div id="storage-capacity"></div>
                    </dib>
                    <div class="col-12 d-flex justify-content-between px-4">
                        <div class="col-2 mt-5 text-center">
                            <button type="button" class="btn rounded-pill me-2 btn-secondary">واگرد</button>
                            <button type="button" class="btn rounded-pill me-2 btn-info">پیشگرد</button>
                        </div>
                        <div class="col-2 mt-5 text-center">
                            <button type="button" class="btn rounded-pill me-2 btn-primary">ذخیره</button>
                            <button type="button" class="btn rounded-pill me-2 btn-warning">انصراف</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
