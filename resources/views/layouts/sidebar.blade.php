  <div class="sidebar">

      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
              @if (Auth::user()->profile_picture)
                  <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" class="img-circle elevation-2"
                      alt="User Image">
              @else
                  <img src="{{ asset('storage/profile_pictures/5Tm4yJureN5x0wZU7NdO3rkTFhJ29l8XLVx1CkaS.png') }}" class="img-circle elevation-2"
                      alt="User Image">
              @endif

          </div>
          <div class="info">
              <a href="#" class="d-block">{{ Auth::user()->name }} ({{ Auth::user()->roles->role_name }}) </a>
        
          </div>
      </div>

      <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
              data-accordion="false">

              <li class="nav-item">
                  <a href="{{ route('home') }}" class="nav-link">
                      <i class="nav-icon fas fa-tachometer-alt"></i>
                      <p>
                          Dashboard
                      </p>
                  </a>
              </li>
              <li class="nav-item">
                    <a href="{{ route('offer_letters.test') }}" class="nav-link">
                        <i class="nav-icon fa fa-file-alt"></i>
                        <p>Offer Letters</p>
                    </a>
                </li>

           
              <li class="nav-item">
                  <a href="#" class="nav-link">
                      <i class="nav-icon fas fa-user"></i>
                      <p>
                          Students
                          <i class="fas fa-angle-left right"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview">
                      <li class="nav-item">
                          <a href="{{ route('add_students') }}" class="nav-link">
                              <i class="nav-icon fa fa-graduation-cap"></i>
                              <p>
                                  Add Student
                              </p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('view_students') }}" class="nav-link">
                              <i class="nav-icon fa fa-eye"></i>
                              <p>
                                  Students List
                              </p>
                          </a>
                      </li>
                  </ul>
              </li>
                 @if(Auth::user()->role_id == 1 || Auth::user()->role_id == 2)
                    <li class="nav-item">
                        <a href="{{ route('documents.verify') }}" class="nav-link">
                            <i class="nav-icon fa fa-file"></i>
                            <p>Documents </p>
                        </a>
                    </li>
                @endif
              @if (Auth::user()->role_id == 1)
                  <li class="nav-item">
                      <a href="#" class="nav-link">
                          <i class="nav-icon fa fa-book"></i>
                          <p>
                              Course
                              <i class="fas fa-angle-left right"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="{{ route('view_courses') }}" class="nav-link">
                                  <i class="nav-icon fa fa-graduation-cap"></i>
                                  <p>
                                      Manage Courses
                                  </p>
                              </a>
                          </li>

                      </ul>
                  </li>
                  <li class="nav-item">
                      <a href="#" class="nav-link">
                          <i class="nav-icon fa fa-user"></i>
                          <p>
                              Users
                              <i class="fas fa-angle-left right"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">

                          <li class="nav-item">
                              <a href="{{ route('register') }}" target="_blank" class="nav-link">
                                  <i class="nav-icon fa fa-user"></i>
                                  <p>
                                      Create Employee
                                  </p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="{{ route('view_employees') }}" class="nav-link">
                                  <i class="nav-icon fa fa-user"></i>
                                  <p>
                                      View Employees
                                  </p>
                              </a>
                          </li>

                      </ul>
                  </li>
              @endif
              <li class="nav-item">
                  <a href="#" class="nav-link">
                      <i class="nav-icon fa fa-credit-card"></i>
                      <p>
                          Payments
                          <i class="fas fa-angle-left right"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview">

                      <li class="nav-item">
                          <a href="{{ route('payments_approve') }}" class="nav-link">
                              <i class="nav-icon fa fa-eye"></i>
                              <p>
                                  Payments Approve
                              </p>
                          </a>
                      </li>

                      @if (Auth::user()->role_id == 1)
                          <li class="nav-item">
                              <a href="{{ route('feature_not_avail') }}" class="nav-link">
                                  <i class="nav-icon fa fa-eye"></i>
                                  <p>
                                      All invoices
                                  </p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a href="{{ route('view_promotions') }}" class="nav-link">
                                  <i class="nav-icon fa fa-eye"></i>
                                  <p>
                                      Promotions
                                  </p>
                              </a>
                          </li>
                      @endif
                      <li class="nav-item">
                          <a href="{{ route('course_installments') }}" class="nav-link">
                              <i class="nav-icon fa fa-eye"></i>
                              <p>
                                  Course Installments
                              </p>
                          </a>
                      </li>

                  </ul>
              </li>
              @if (Auth::user()->role_id == 1)
                  <li class="nav-item">
                      <a href="#" class="nav-link">
                          <i class="nav-icon fa fa-cog"></i>
                          <p>
                              Settings
                              <i class="fas fa-angle-left right"></i>
                          </p>
                      </a>
                      <ul class="nav nav-treeview">
                          <li class="nav-item">
                              <a href="{{ route('feature_not_avail') }}" class="nav-link">
                                  <i class="nav-icon fa fa-lock"></i>
                                  <p>
                                      Manage Permissions
                                  </p>
                              </a>
                          </li>
                      </ul>
                  </li>
              @endif
          </ul>





      </nav>

  </div>
