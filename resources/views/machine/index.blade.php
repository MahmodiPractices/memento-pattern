@extends('layout.master')
@section('content')
    <div class="col-12">
        <x-alert/>
        <div class="card">
            <div class="d-flex flex-row justify-content-between align-items-center">
                <h5 class="card-header heading-color">فهرست دستگاه ها</h5>
                <a href="{{ route('machine.create') }}" class="btn btn-outline-success m-3 px-1">
                    <i class="bx bxs-plus-circle px-1"></i>
                    <span class="px-2">ایجاد دستگاه جدید</span>
                </a>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                    <tr>
                        <th>نام دستگاه</th>
                        <th>تعداد هسته پردازشی</th>
                        <th>حافظه موقت</th>
                        <th>حافظه دائمی</th>
                        <th>عمل‌ها</th>
                    </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    @foreach($machines as $machine)
                        <tr>
                            <td><strong>{{ $machine->name }}</strong></td>
                            <td>{{ $machine->core }} هسته</td>
                            <td>{{ $machine->ram }} گیگابایت</td>
                            <td>{{ $machine->storage }} گیگابایت</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('machine.edit', $machine) }}"><i class="bx bx-edit-alt me-1"></i> ویرایش</a>
                                        <form action="{{ route('machine.destroy', $machine) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="dropdown-item">
                                                <i class="bx bx-trash me-1"></i> حذف
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
