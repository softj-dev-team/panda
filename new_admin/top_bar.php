<!-- Topbar -->


        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">





          <!-- Sidebar Toggle (Topbar) -->


          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">


            <i class="fa fa-bars"></i>


          </button>








          <!-- Topbar Navbar -->


          <ul class="navbar-nav ml-auto">








            <!-- Nav Item - User Information -->


            <li class="nav-item dropdown no-arrow">


              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">


                <span class="mr-2 d-none d-lg-inline text-gray-600 small">admin</span>


                <img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">


              </a>


              <!-- Dropdown - User Information -->


              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">


                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">


                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>


                  로그아웃


                </a>


              </div>


            </li>





          </ul>





        </nav>


        <!-- End of Topbar -->

        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">로그아웃을 진행하시겠습니까?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">현재 사이트를 벗어나려면 로그아웃을 진행해주세요.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">취소</button>
          <a class="btn btn-primary" href="./logout.php">로그아웃</a>
        </div>
      </div>
    </div>
  </div>