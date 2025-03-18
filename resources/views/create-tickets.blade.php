@extends('layouts/layout')
@section('title', 'Dashboard')
@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Ticket List</h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="index.html" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">Ticket Management</li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">create tickets</li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page title-->
                <!--begin::Actions-->
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <!--begin::Filter menu-->
                    <div class="m-0">
                        <!--begin::Menu toggle-->

                        <!--end::Menu toggle-->
                        <!--begin::Menu 1-->

                        <!--end::Menu 1-->
                    </div>
                    <!--end::Filter menu-->
                    <!--begin::Secondary button-->
                    <!--end::Secondary button-->
                    <!--begin::Primary button-->
                   
                    <!--end::Primary button-->
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-xxl">
                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-6">
                        <!--begin::Card title-->
                        
                        <!--begin::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <!--begin::Toolbar-->
                          
                            <!--end::Toolbar-->
                            <form method="POST" id="kt_modal_new_ticket_form" class="form" action="#">
														<!--begin::Heading-->
														<div class="mb-13 text-center">
															<!--begin::Title-->
															<h1 class="mb-3">Create Ticket</h1>
															<!--end::Title-->
												
														</div>
														<!--end::Heading-->
														<!--begin::Input group-->
														<div class="d-flex flex-column mb-8 fv-row">
															<!--begin::Label-->
															<label class="d-flex align-items-center fs-6 fw-semibold mb-2">
																<span class="required">Subject</span>
																<span class="ms-2" data-bs-toggle="tooltip" title="Specify a subject for your issue">
																	<i class="ki-duotone ki-information fs-7">
																		<span class="path1"></span>
																		<span class="path2"></span>
																		<span class="path3"></span>
																	</i>
																</span>
															</label>
															<!--end::Label-->
															<input type="text" class="form-control form-control-solid" placeholder="Enter your ticket subject" name="subject" />
														</div>
														<!--end::Input group-->
														<!--begin::Input group-->
														<!--end::Input group-->
														<!--begin::Input group-->
														<div class="row g-9 mb-8">
															<!--begin::Col-->
															<div class="col-md-12 fv-row">
																<label class="required fs-6 fw-semibold mb-2">Status</label>
																<select class="form-select form-select-solid" data-control="select2" data-placeholder="Open" data-hide-search="true">
																	<option value=""></option>
																	<option value="open" selected="selected">Open</option>
																	<option value="in_progress">in progress</option>
																	<option value="resolved">Resolved</option>
																	<option value="closed">Closed</option>
																</select>
															</div>
															<!--end::Col-->
															<!--begin::Col-->
															
															<!--end::Col-->
														</div>
														<!--end::Input group-->
														<!--begin::Input group-->
														<div class="d-flex flex-column mb-8 fv-row">
															<label class="fs-6 fw-semibold mb-2">Description</label>
															<textarea class="form-control form-control-solid" rows="4" name="description" placeholder="Type your ticket description"></textarea>
														</div>
														<!--end::Input group-->
														<!--begin::Input group-->
														<div class="fv-row mb-8">
															<label class="fs-6 fw-semibold mb-2">Attachments</label>
															<!--begin::Dropzone-->
															<div class="dropzone" id="kt_modal_create_ticket_attachments">
																<!--begin::Message-->
																<div class="dz-message needsclick align-items-center">
																	<!--begin::Icon-->
																	<i class="ki-duotone ki-file-up fs-3hx text-primary">
																		<span class="path1"></span>
																		<span class="path2"></span>
																	</i>
																	<!--end::Icon-->
																	<!--begin::Info-->
																	<div class="ms-4">
																		<h3 class="fs-5 fw-bold text-gray-900 mb-1">Drop files here or click to upload.</h3>
																		<span class="fw-semibold fs-7 text-gray-500">Upload up to 10 files</span>
																	</div>
																	<!--end::Info-->
																</div>
															</div>
															<!--end::Dropzone-->
														</div>
														<!--end::Input group-->
													
														<!--end::Input group-->
														<!--begin::Actions-->
														<div class="text-center">
                                                            <!--begin::Submit button-->
                                                            	<button type="submit" id="kt_modal_new_ticket_submit" class="btn btn-primary">
																<span class="indicator-label">Submit</span>
																<span class="indicator-progress">Please wait... 
																<span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
															</button>
														</div>
														<!--end::Actions-->
													</form>

                            <!--end::Modal - New Card-->
                            <!--begin::Modal - Add task-->
                            
                            <!--end::Modal - Add task-->
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                  
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
    <!--begin::Footer-->
    <div id="kt_app_footer" class="app-footer">
        <!--begin::Footer container-->
        <div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
            <!--begin::Copyright-->
            <div class="text-gray-900 order-2 order-md-1">
                <span class="text-muted fw-semibold me-1">2025&copy;</span>
                <a href="https://ringo.ng" target="_blank" class="text-gray-800 text-hover-primary">Ringo</a>
            </div>
            <!--end::Copyright-->
            <!--begin::Menu-->
            <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
                <li class="menu-item">
                    <a href="https://ringo.ng" target="_blank" class="menu-link px-2">About</a>
                </li>
                <li class="menu-item">
                    <a href="https://ringo.ng" target="_blank" class="menu-link px-2">Support</a>
                </li>
                <li class="menu-item">
                    <a href="https://ringo.ng" target="_blank" class="menu-link px-2">Purchase</a>
                </li>
            </ul>
            <!--end::Menu-->
        </div>
        <!--end::Footer container-->
    </div>
    <!--end::Footer-->
</div>

@endsection

@push('scripts')
<!--begin::Custom Javascript(used for this page only)-->
<script src="assets/js/custom/apps/support-center/tickets/create.js"></script>
<script src="assets/js/widgets.bundle.js"></script>
<script src="assets/js/custom/widgets.js"></script>
<script src="assets/js/custom/apps/chat/chat.js"></script>
<script src="assets/js/custom/utilities/modals/upgrade-plan.js"></script>
<script src="assets/js/custom/utilities/modals/create-app.js"></script>
<script src="assets/js/custom/utilities/modals/users-search.js"></script>
<script src="assets/plugins/global/plugins.bundle.js"></script>

@endpush
