@extends('layouts.user-layout')

@section('content')

            <!-- Main content -->
            <section class="content">
                <!-- Content Header (Page header) -->
                <div class="content-header">
                    <div class="d-flex align-items-center">
                        <div class="w-p100 d-md-flex align-items-center justify-content-between">
                            <h3 class="page-title">Payment List</h3>
                            <div class="d-inline-block align-items-center">
                                <nav>
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="#"><i class="mdi mdi-home-outline"></i></a></li>
                                        <li class="breadcrumb-item" aria-current="page">Payment</li>
                                        <li class="breadcrumb-item active" aria-current="page">Payment List</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-10 col-lg-9 col-12">
                        <div class="box">
                            <div class="box-header with-border">
                                <h4 class="box-title">Payment List</h4>
                                <h6 class="box-subtitle">Export Invoice List to Copy, CSV, Excel, PDF & Print</h6>
                            </div>
                            <div class="box-body">
                                <div class="table-responsive">

                                    <table id="example" class="table table-lg invoice-archive">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Period</th>
                                            <th>Issued to</th>
                                            <th>Status</th>
                                            <th>Issue date</th>
                                            <th>Due date</th>
                                            <th>Amount</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>#0025</td>
                                            <td>February 2018</td>
                                            <td>
                                                <h6 class="mb-0">
                                                    <a href="#">Samji</a>
                                                    <span class="d-block text-muted">Payment method: Rave</span>
                                                </h6>
                                            </td>
                                            <td>
                                                <select name="status" class="form-control" data-placeholder="Select status">
                                                    <option value="overdue">Overdue</option>
                                                    <option value="hold" selected>On hold</option>
                                                    <option value="pending">Pending</option>
                                                    <option value="paid">Paid</option>
                                                    <option value="invalid">Invalid</option>
                                                    <option value="cancel">Canceled</option>
                                                </select>
                                            </td>
                                            <td>
                                                April 18, 2018
                                            </td>
                                            <td>
                                                <span class="badge badge-pill badge-success">Paid on Mar 16, 2018</span>
                                            </td>
                                            <td>
                                                <h6 class="mb-0 font-weight-bold">$36,890 <span class="d-block text-muted font-weight-normal">VAT $4,859</span></h6>
                                            </td>
                                            <td class="text-center">
                                                <div class="list-icons d-inline-flex">
                                                    <a href="#" class="list-icons-item mr-10"><i class="fa fa-eye-slash"></i></a>
                                                    <div class="list-icons-item dropdown">
                                                        <a href="#" class="list-icons-item dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file-text"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a href="#" class="dropdown-item"><i class="fa fa-download"></i> Download</a>
                                                            <a href="#" class="dropdown-item"><i class="fa fa-print"></i> Print</a>
                                                            <div class="dropdown-divider"></div>
                                                            <a href="#" class="dropdown-item"><i class="fa fa-pencil"></i> Edit</a>
                                                            <a href="#" class="dropdown-item"><i class="fa fa-remove"></i> Remove</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>#0024</td>
                                            <td>February 2018</td>
                                            <td>
                                                <h6 class="mb-0">
                                                    <a href="#">Samji</a>
                                                    <span class="d-block text-muted">Payment method: Rave</span>
                                                </h6>
                                            </td>
                                            <td>
                                                <select name="status" class="form-control" data-placeholder="Select status">
                                                    <option value="overdue">Overdue</option>
                                                    <option value="hold">On hold</option>
                                                    <option value="pending">Pending</option>
                                                    <option value="paid" selected>Paid</option>
                                                    <option value="invalid">Invalid</option>
                                                    <option value="cancel">Canceled</option>
                                                </select>
                                            </td>
                                            <td>
                                                April 17, 2018
                                            </td>
                                            <td>
                                                <span class="badge badge-pill badge-warning">5 days</span>
                                            </td>
                                            <td>
                                                <h6 class="mb-0 font-weight-bold">$8,854 <span class="d-block text-muted font-weight-normal">VAT $4,785</span></h6>
                                            </td>
                                            <td class="text-center">
                                                <div class="list-icons d-inline-flex">
                                                    <a href="#" class="list-icons-item mr-10"><i class="fa fa-eye-slash"></i></a>
                                                    <div class="list-icons-item dropdown">
                                                        <a href="#" class="list-icons-item dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file-text"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a href="#" class="dropdown-item"><i class="fa fa-download"></i> Download</a>
                                                            <a href="#" class="dropdown-item"><i class="fa fa-print"></i> Print</a>
                                                            <div class="dropdown-divider"></div>
                                                            <a href="#" class="dropdown-item"><i class="fa fa-pencil"></i> Edit</a>
                                                            <a href="#" class="dropdown-item"><i class="fa fa-remove"></i> Remove</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>#0023</td>
                                            <td>February 2018</td>
                                            <td>
                                                <h6 class="mb-0">
                                                    <a href="#">Samji</a>
                                                    <span class="d-block text-muted">Payment method: Rave</span>
                                                </h6>
                                            </td>
                                            <td>
                                                <select name="status" class="form-control" data-placeholder="Select status">
                                                    <option value="overdue">Overdue</option>
                                                    <option value="hold">On hold</option>
                                                    <option value="pending">Pending</option>
                                                    <option value="paid" selected>Paid</option>
                                                    <option value="invalid">Invalid</option>
                                                    <option value="cancel">Canceled</option>
                                                </select>
                                            </td>
                                            <td>
                                                April 17, 2018
                                            </td>
                                            <td>
                                                <span class="badge badge-pill badge-primary">27 days</span>
                                            </td>
                                            <td>
                                                <h6 class="mb-0 font-weight-bold">$12,854 <span class="d-block text-muted font-weight-normal">VAT $7,845</span></h6>
                                            </td>
                                            <td class="text-center">
                                                <div class="list-icons d-inline-flex">
                                                    <a href="#" class="list-icons-item mr-10"><i class="fa fa-eye-slash"></i></a>
                                                    <div class="list-icons-item dropdown">
                                                        <a href="#" class="list-icons-item dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file-text"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a href="#" class="dropdown-item"><i class="fa fa-download"></i> Download</a>
                                                            <a href="#" class="dropdown-item"><i class="fa fa-print"></i> Print</a>
                                                            <div class="dropdown-divider"></div>
                                                            <a href="#" class="dropdown-item"><i class="fa fa-pencil"></i> Edit</a>
                                                            <a href="#" class="dropdown-item"><i class="fa fa-remove"></i> Remove</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>#0022</td>
                                            <td>January 2018</td>
                                            <td>
                                                <h6 class="mb-0">
                                                    <a href="#">Samji</a>
                                                    <span class="d-block text-muted">Payment method: Rave</span>
                                                </h6>
                                            </td>
                                            <td>
                                                <select name="status" class="form-control" data-placeholder="Select status">
                                                    <option value="overdue">Overdue</option>
                                                    <option value="hold">On hold</option>
                                                    <option value="pending">Pending</option>
                                                    <option value="paid">Paid</option>
                                                    <option value="invalid">Invalid</option>
                                                    <option value="cancel" selected>Canceled</option>
                                                </select>
                                            </td>
                                            <td>
                                                January 15, 2018
                                            </td>
                                            <td>
                                                <span class="badge badge-pill badge-primary">12 days</span>
                                            </td>
                                            <td>
                                                <h6 class="mb-0 font-weight-bold">$458 <span class="d-block text-muted font-weight-normal">VAT $985</span></h6>
                                            </td>
                                            <td class="text-center">
                                                <div class="list-icons d-inline-flex">
                                                    <a href="#" class="list-icons-item mr-10"><i class="fa fa-eye-slash"></i></a>
                                                    <div class="list-icons-item dropdown">
                                                        <a href="#" class="list-icons-item dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file-text"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a href="#" class="dropdown-item"><i class="fa fa-download"></i> Download</a>
                                                            <a href="#" class="dropdown-item"><i class="fa fa-print"></i> Print</a>
                                                            <div class="dropdown-divider"></div>
                                                            <a href="#" class="dropdown-item"><i class="fa fa-pencil"></i> Edit</a>
                                                            <a href="#" class="dropdown-item"><i class="fa fa-remove"></i> Remove</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>#0021</td>
                                            <td>January 2018</td>
                                            <td>
                                                <h6 class="mb-0">
                                                    <a href="#">Samji</a>
                                                    <span class="d-block text-muted">Payment method: Rave</span>
                                                </h6>
                                            </td>
                                            <td>
                                                <select name="status" class="form-control" data-placeholder="Select status">
                                                    <option value="overdue" selected>Overdue</option>
                                                    <option value="hold">On hold</option>
                                                    <option value="pending">Pending</option>
                                                    <option value="paid">Paid</option>
                                                    <option value="invalid">Invalid</option>
                                                    <option value="cancel">Canceled</option>
                                                </select>
                                            </td>
                                            <td>
                                                January 10, 2018
                                            </td>
                                            <td>
                                                <span class="badge badge-pill badge-danger">- 3 days</span>
                                            </td>
                                            <td>
                                                <h6 class="mb-0 font-weight-bold">$845 <span class="d-block text-muted font-weight-normal">VAT $98</span></h6>
                                            </td>
                                            <td class="text-center">
                                                <div class="list-icons d-inline-flex">
                                                    <a href="#" class="list-icons-item mr-10"><i class="fa fa-eye-slash"></i></a>
                                                    <div class="list-icons-item dropdown">
                                                        <a href="#" class="list-icons-item dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file-text"></i></a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a href="#" class="dropdown-item"><i class="fa fa-download"></i> Download</a>
                                                            <a href="#" class="dropdown-item"><i class="fa fa-print"></i> Print</a>
                                                            <div class="dropdown-divider"></div>
                                                            <a href="#" class="dropdown-item"><i class="fa fa-pencil"></i> Edit</a>
                                                            <a href="#" class="dropdown-item"><i class="fa fa-remove"></i> Remove</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-12">
                        <div class="box box-inverse box-success">
                            <div class="box-body">
                                <div class="flexbox">
                                    <h5>Invoice</h5>
                                    <div class="dropdown">
                                        <span class="dropdown-toggle no-caret" data-toggle="dropdown"><i class="ion-android-more-vertical rotate-90"></i></span>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#"><i class="ion-android-list"></i> Details</a>
                                            <a class="dropdown-item" href="#"><i class="ion-android-add"></i> Add new</a>
                                            <a class="dropdown-item" href="#"><i class="ion-android-refresh"></i> Refresh</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center my-2">
                                    <div class="font-size-60">2,064</div>
                                    <span>Total Invoice</span>
                                </div>
                            </div>
                        </div>
                        <div class="box box-inverse box-primary">
                            <div class="box-body">
                                <div class="flexbox">
                                    <h5>Re Generate Invoice</h5>
                                    <div class="dropdown">
                                        <span class="dropdown-toggle no-caret" data-toggle="dropdown"><i class="ion-android-more-vertical rotate-90"></i></span>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#"><i class="ion-android-list"></i> Details</a>
                                            <a class="dropdown-item" href="#"><i class="ion-android-add"></i> Add new</a>
                                            <a class="dropdown-item" href="#"><i class="ion-android-refresh"></i> Refresh</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center my-2">
                                    <div class="font-size-60">1,738</div>
                                    <span>Re Generate Invoice</span>
                                </div>
                            </div>
                        </div>
                        <div class="box box-inverse box-danger">
                            <div class="box-body">
                                <div class="flexbox">
                                    <h5>Overdue Payment Invoice</h5>
                                    <div class="dropdown">
                                        <span class="dropdown-toggle no-caret" data-toggle="dropdown"><i class="ion-android-more-vertical rotate-90"></i></span>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#"><i class="ion-android-list"></i> Details</a>
                                            <a class="dropdown-item" href="#"><i class="ion-android-add"></i> Add new</a>
                                            <a class="dropdown-item" href="#"><i class="ion-android-refresh"></i> Refresh</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center my-2">
                                    <div class="font-size-60">1100</div>
                                    <span>Overdue Payment Invoice</span>
                                </div>
                            </div>

                        </div>
                        <div class="box box-inverse box-warning">
                            <div class="box-body">
                                <div class="flexbox">
                                    <h5>Pending Invoice</h5>
                                    <div class="dropdown">
                                        <span class="dropdown-toggle no-caret" data-toggle="dropdown"><i class="ion-android-more-vertical rotate-90"></i></span>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item" href="#"><i class="ion-android-list"></i> Details</a>
                                            <a class="dropdown-item" href="#"><i class="ion-android-add"></i> Add new</a>
                                            <a class="dropdown-item" href="#"><i class="ion-android-refresh"></i> Refresh</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center my-2">
                                    <div class="font-size-60">964</div>
                                    <span>Pending</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- /.content -->
@endsection
