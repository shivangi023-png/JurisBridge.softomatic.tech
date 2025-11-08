<div class="customizer-content p-2">
  <div id="cust-alert"></div>
    <h4 class="text-uppercase mb-0">Send Mail
        <a href="#" class="btn btn-outline-success btn-sm float-right mr-5 send_mail">Send Mail</a>
        <a class="customizer-close" href="#"><i class="bx bx-x"></i></a>
    </h4>
   
    <hr class="layout">
    <div class="theme-layouts">
        <div class="d-flex justify-content-start">
            <form action="">
                <input type="hidden" value="{{url('/')}}" id="base_url">
                <div class="row mb-2">
                    <div class="col-12">
                        <div class="form-group template_list">
                            <select class="form-control mailTemplate">
                                <option value="">Select Template</option>

                            </select>
                        </div>
                    </div>
                    <span class="template_name_err valid_err ml-1"></span>
                </div>

               

                <div class="row mb-2">
                    <div class="col-12">
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="hidden" class="form-control quotation_details_id">
                                <input type="text" name="client_email" id="clientEmail"
                                    class="form-control client_email" placeholder="To">
                            </div>
                        </fieldset>
                    </div>
                    <span class="client_email_err valid_err  ml-1"></span>
                </div>

                <div class="row mb-2">
                    <div class="col-12 cc_link">
                        <a href="javascript:void();">Add CC</a>
                    </div>
                    <div class="col-11 cc_input" style="display:none;">
                        <div class="form-group">
                            <input type="text" name="cc_email" class="form-control cc_email" placeholder="CC">
                        </div>
                    </div>
                    <div class="col-1 cc_close" style="display:none;">
                        <a href="javascript:void();" class="btn btn-icon rounded-circle btn-light-secondary"><i
                                class="bx bx-x"></i></a>
                    </div>
                    <span class="cc_email_err valid_err ml-1"></span>
                </div>
                <div class="row quot_editor">
                    <div class="col-12">
                        <h5>Subject</h5>
                        <div class="full-editor">
                            <div class="row">
                                <div class="col-12">
                                   <textarea class="form-control subject_editor"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span class="subject_editor_err valid_err ml-1 editor_error"></span>
                </div>


                <div class="row quot_editor">
                    <div class="col-12">
                        <h5>Compose Message</h5>
                        <fieldset class="full-editor">
                            <div class="row">
                                <div class="col-12">
                                    <div id="full-body-wrapper">
                                        <div id="full-body-container">
                                            <div class="body_editor">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <span class="body_editor_err valid_err ml-1 editor_error"></span>
                </div>


                <div class="row">
                    <div class="col-12">
                        <h5>Quotations</h5>
                        <fieldset class="form-group">
                            <div class="input-group">
                                <input type="text" name="quotation_file" id="quotationFile"
                                    class="form-control quotation_file" readonly>
                                <input type="hidden" class="form-control unit">
                            </div>
                        </fieldset>
                    </div>
                </div>
                <span class="quotation_file_err valid_err ml-1"></span>
            </form>
        </div>
    </div>
</div>

<!-- Theme options starts -->
<!-- <h5 class="mt-1">Theme Layout</h5>
    <div class="theme-layouts">
        <div class="d-flex justify-content-start">
            <div class="mx-50">
                <fieldset>
                    <div class="radio">
                        <input type="radio" name="layoutOptions" value="false" id="radio-light" class="layout-name"
                            data-layout="" checked>
                        <label for="radio-light">Light</label>
                    </div>
                </fieldset>
            </div>
            <div class="mx-50">
                <fieldset>
                    <div class="radio">
                        <input type="radio" name="layoutOptions" value="false" id="radio-dark" class="layout-name"
                            data-layout="dark-layout">
                        <label for="radio-dark">Dark</label>
                    </div>
                </fieldset>
            </div>
            <div class="mx-50">
                <fieldset>
                    <div class="radio">
                        <input type="radio" name="layoutOptions" value="false" id="radio-semi-dark" class="layout-name"
                            data-layout="semi-dark-layout">
                        <label for="radio-semi-dark">Semi Dark</label>
                    </div>
                </fieldset>
            </div>
        </div>
    </div> -->
<!-- Theme options starts -->
<!-- <hr> -->

<!-- Menu Colors Starts -->
<!-- <div id="customizer-theme-colors">
    <h5>Menu Colors</h5>
    <ul class="list-inline unstyled-list">
      <li class="color-box bg-primary selected" data-color="theme-primary"></li>
      <li class="color-box bg-success" data-color="theme-success"></li>
      <li class="color-box bg-danger" data-color="theme-danger"></li>
      <li class="color-box bg-info" data-color="theme-info"></li>
      <li class="color-box bg-warning" data-color="theme-warning"></li>
      <li class="color-box bg-dark" data-color="theme-dark"></li>
    </ul>
    <hr>
  </div> -->
<!-- Menu Colors Ends -->
<!-- Menu Icon Animation Starts -->
<!-- <div id="menu-icon-animation">
    <div class="d-flex justify-content-between align-items-center">
      <div class="icon-animation-title">
        <h5 class="pt-25">Icon Animation</h5>
      </div>
      <div class="icon-animation-switch">
        <div class="custom-control custom-switch">
          <input type="checkbox" class="custom-control-input" checked id="icon-animation-switch">
          <label class="custom-control-label" for="icon-animation-switch"></label>
        </div>
      </div>
    </div>
    <hr>
  </div> -->
<!-- Menu Icon Animation Ends -->
<!-- Collapse sidebar switch starts -->
<!-- <div class="collapse-sidebar d-flex justify-content-between align-items-center">
    <div class="collapse-option-title">
      <h5 class="pt-25">Collapse Menu</h5>
    </div>
    <div class="collapse-option-switch">
      <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input" id="collapse-sidebar-switch">
        <label class="custom-control-label" for="collapse-sidebar-switch"></label>
      </div>
    </div>
  </div> -->
<!-- Collapse sidebar switch Ends -->
<!-- <hr> -->

<!-- Navbar colors starts -->
<!-- <div id="customizer-navbar-colors">
    <h5>Navbar Colors</h5>
    <ul class="list-inline unstyled-list">
      <li class="color-box bg-white border selected" data-navbar-default=""></li>
      <li class="color-box bg-primary" data-navbar-color="bg-primary"></li>
      <li class="color-box bg-success" data-navbar-color="bg-success"></li>
      <li class="color-box bg-danger" data-navbar-color="bg-danger"></li>
      <li class="color-box bg-info" data-navbar-color="bg-info"></li>
      <li class="color-box bg-warning" data-navbar-color="bg-warning"></li>
      <li class="color-box bg-dark" data-navbar-color="bg-dark"></li>
    </ul>
    <small><strong>Note :</strong> This option with work only on sticky navbar when you scroll page.</small>
    <hr>
  </div> -->
<!-- Navbar colors starts -->
<!-- Navbar Type Starts -->
<!-- <h5>Navbar Type</h5>
  <div class="navbar-type d-flex justify-content-start">
    <div class="hidden-ele mx-50">
      <fieldset>
        <div class="radio">
          <input type="radio" name="navbarType" value="false" id="navbar-hidden">
          <label for="navbar-hidden">Hidden</label>
        </div>
      </fieldset>
    </div>
    <div class="mx-50">
      <fieldset>
        <div class="radio">
          <input type="radio" name="navbarType" value="false" id="navbar-static">
          <label for="navbar-static">Static</label>
        </div>
      </fieldset>
    </div>
    <div class="mx-50">
      <fieldset>
        <div class="radio">
          <input type="radio" name="navbarType" value="false" id="navbar-sticky" checked>
          <label for="navbar-sticky">Fixed</label>
        </div>
      </fieldset>
    </div>
  </div>
  <hr> -->
<!-- Navbar Type Starts -->

<!-- Footer Type Starts -->
<!-- <h5>Footer Type</h5>
  <div class="footer-type d-flex justify-content-start">
    <div class="mx-50">
      <fieldset>
        <div class="radio">
          <input type="radio" name="footerType" value="false" id="footer-hidden">
          <label for="footer-hidden">Hidden</label>
        </div>
      </fieldset>
    </div>
    <div class="mx-50">
      <fieldset>
        <div class="radio">
          <input type="radio" name="footerType" value="false" id="footer-static" checked>
          <label for="footer-static">Static</label>
        </div>
      </fieldset>
    </div>
    <div class="mx-50">
      <fieldset>
        <div class="radio">
          <input type="radio" name="footerType" value="false" id="footer-sticky">
          <label for="footer-sticky" class="">Sticky</label>
        </div>
      </fieldset>
    </div>
  </div> -->
<!-- Footer Type Ends -->
<!-- <hr> -->

<!-- Card Shadow Starts-->
<!-- <div class="card-shadow d-flex justify-content-between py-25 align-items-center">
    <div class="hide-scroll-title">
      <h5 class="pt-25">Card Shadow</h5>
    </div>
    <div class="card-shadow-switch">
      <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input" checked id="card-shadow-switch">
        <label class="custom-control-label" for="card-shadow-switch"></label>
      </div>
    </div>
  </div> -->
<!-- Card Shadow Ends-->
<!-- <hr> -->

<!-- Hide Scroll To Top Starts-->
<!-- <div class="hide-scroll-to-top d-flex justify-content-between align-items-center py-25">
    <div class="hide-scroll-title">
      <h5 class="pt-25">Hide Scroll To Top</h5>
    </div>
    <div class="hide-scroll-top-switch">
      <div class="custom-control custom-switch">
        <input type="checkbox" class="custom-control-input" id="hide-scroll-top-switch">
        <label class="custom-control-label" for="hide-scroll-top-switch"></label>
      </div>
    </div>
  </div> -->
<!-- Hide Scroll To Top Ends-->
</div>