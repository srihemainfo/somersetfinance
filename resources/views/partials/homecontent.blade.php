<style>
    .chart{
    border: 1px solid #e1e1e1;
    padding: 10px;
    border-radius: 6px;
    }
    .card-body{
        text-align:center;
    }
    .card{
    background: #ededed;
    }
</style>

<div class="content">
     <div class="row justify-content-center">
          
                                 <div class="col-md-6">
                                      <h2>Partner List</h2>
                                       <p style="float: right;margin: -27px 0 0 0;">See All</p>
     <div id="carouselExampleControls" class="carousel slide" data-intervall="false">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <div class="row">
            <div class="col-md-3 ">

              <div class="card">
                <img class="card-img-top" src="https://somerset.senthil.in.net/partner_images/human1.png" alt="Card image cap">
                <div class="card-body">
                  <p class="card-text">Johnm</p>
                </div>
              </div>


            </div>
            <div class="col-md-3">

              <div class="card">
                <img class="card-img-top" src="https://somerset.senthil.in.net/partner_images/human2.png" alt="Card image cap">
                <div class="card-body">
                  <p class="card-text">MAry</p>
                </div>
              </div>


            </div>
            <div class="col-md-3">


              <div class="card">
                <img class="card-img-top" src="https://somerset.senthil.in.net/partner_images/human3.png" alt="Card image cap">
                <div class="card-body">
                  <p class="card-text">Maria</p>
                </div>
              </div>


            </div>
            <div class="col-md-3">


              <div class="card">
                <img class="card-img-top" src="https://somerset.senthil.in.net/partner_images/human4.png" alt="Card image cap">
                <div class="card-body">
                  <p class="card-text">Nancy</p>
                </div>
              </div>

            </div>
          
          </div>

        </div>
        <div class="carousel-item">
          <div class="row">
            <div class="col-md-3">


              <div class="card">
                <img class="card-img-top" src="https://somerset.senthil.in.net/partner_images/human1.png" alt="Card image cap">
                <div class="card-body">
                  <p class="card-text">Peterr</p>
                </div>
              </div>


            </div>
            <div class="col-md-3">

              <div class="card">
                <img class="card-img-top" src="https://somerset.senthil.in.net/partner_images/human2.png" alt="Card image cap">
                <div class="card-body">
                  <p class="card-text">Sundari</p>
                </div>
              </div>

            </div>
            <div class="col-md-3">

              <div class="card">
                <img class="card-img-top" src="https://somerset.senthil.in.net/partner_images/human3.png" alt="Card image cap">
                <div class="card-body">
                  <p class="card-text">Sheeba</p>
                </div>
              </div>

            </div>
            <div class="col-md-3">

              <div class="card">
                <img class="card-img-top" src="https://somerset.senthil.in.net/partner_images/human4.png" alt="Card image cap">
                <div class="card-body">
                  <p class="card-text">Lvly</p>
                </div>
              </div>

            </div>
          </div>
        </div>
        <div class="carousel-item">
          <div class="row">
            <div class="col-md-3">

              <div class="card">
                <img class="card-img-top" src="https://somerset.senthil.in.net/partner_images/human1.png" alt="Card image cap">
                <div class="card-body">
                  <p class="card-text">John</p>
                </div>
              </div>

            </div>
            <div class="col-md-3">

              <div class="card">
                <img class="card-img-top" src="https://somerset.senthil.in.net/partner_images/human2.png" alt="Card image cap">
                <div class="card-body">
                  <p class="card-text">Bodhi</p>
                </div>
              </div>

            </div>
            <div class="col-md-3">

              <div class="card">
                <img class="card-img-top" src="https://somerset.senthil.in.net/partner_images/human3.png">
                <div class="card-body">
                  <p class="card-text">Tamil</p>
                </div>
              </div>

            </div>
            <div class="col-md-3">

              <div class="card">
                <img class="card-img-top" src="https://somerset.senthil.in.net/partner_images/human4.png" alt="Card image cap">
                <div class="card-body">
                  <p class="card-text">Sandy</p>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
      <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>

 
    </div>
                                <div class="col-md-6 mb-3">
                                    <h2>Statistics</h2>
                                    <p style="float: right;margin: -27px 0 0 0;">This Month</p>
                                   <div class="chart">
                                       <canvas id="barChart"></canvas>
                                   </div>
                                </div>
                            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            Case List
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                              <li class="nav-item">
                                <a class="nav-link active" id="open-cases-tab" data-toggle="tab" href="#open-cases" role="tab" aria-controls="open-cases" aria-selected="true">Open Cases</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" id="completed-cases-tab" data-toggle="tab" href="#completed-cases" role="tab" aria-controls="completed-cases" aria-selected="false">Completed Cases</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" id="canceled-cases-tab" data-toggle="tab" href="#canceled-cases" role="tab" aria-controls="canceled-cases" aria-selected="false">Canceled Cases</a>
                              </li>
                            </ul>
                            
                            <div class="tab-content" id="myTabContent">
                              <div class="tab-pane fade show active" id="open-cases" role="tabpanel" aria-labelledby="open-cases-tab">
                                <div class="card-body">
                                    <table class=" table table-striped table-hover ajaxTable datatable datatable-caseList text-center">
                                        <thead>
                                          <tr>
                                            <th scope="col"></th>
                                            <th scope="col">S.No</th>
                                            <th scope="col">App No.</th>
                                            <th scope="col">Name</th>
                                            @if( $roleTitle == 1)
                                            <th scope="col">Partner</th>
                                            @endif
                                            <th scope="col">Action</th>
                                          </tr>
                                        </thead>
                                      
                                    </table>
                                </div>
                              </div>
                              <div class="tab-pane fade" id="completed-cases" role="tabpanel" aria-labelledby="completed-cases-tab">
                                <div class="card-body">
                                    <table class="table table-striped table-hover ajaxTable datatable datatable-caseList2 text-center">
                                        <thead>
                                          <tr>
                                               <th scope="col"></th>
                                            <th scope="col">S.No</th>
                                            <th scope="col">App No.</th>
                                            <th scope="col">Name</th>
                                            @if( $roleTitle == 1)
                                            <th scope="col">Partner</th>
                                            @endif
                                            <th scope="col">Action</th>
                                          </tr>
                                        </thead>
                                    </table>
                                </div>
                              </div>
                              <div class="tab-pane fade" id="canceled-cases" role="tabpanel" aria-labelledby="canceled-cases-tab">
                                <div class="card-body">
                                    <table class="table table-striped table-hover ajaxTable datatable datatable-caseList3 text-center">
                                        <thead>
                                          <tr>
                                            <th scope="col"></th>
                                            <th scope="col">S.No</th>
                                            <th scope="col">App No.</th>
                                            <th scope="col">Name</th>
                                            @if( $roleTitle == 1)
                                            <th scope="col">Partner</th>
                                            @endif
                                            <th scope="col">Action</th>
                                          </tr>
                                        </thead>
                                    </table>
                                </div>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--<div class="col-lg-6">-->
                <!--    <div class="card">-->
                <!--        <div class="card-header">-->
                <!--            Enquiry List-->
                <!--        </div>-->
                <!--        <div class="card-body">-->
                <!--            <table class=" table table-striped table-hover ajaxTable datatable datatable-caseList4 text-center">-->
                <!--                <thead>-->
                <!--                  <tr>-->
                <!--                    <th scope="col"></th>-->
                <!--                    <th scope="col">S.No</th>-->
                <!--                    <th scope="col">Name</th>-->
                <!--                    @if( $roleTitle == 1)-->
                <!--                        <th scope="col">Partner</th>-->
                <!--                    @endif-->
                <!--                    <th scope="col">Action</th>-->
                <!--                  </tr>-->
                <!--                </thead>-->
                              
                <!--            </table>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
            </div>
        </div>
        <script>
            var ctx = document.getElementById("barChart").getContext('2d');
var barChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ["Week1", "Week2", "Week3", "Week4", "Week5", "Week6", "Week7"],
    datasets: [{
      label: 'statistics-1',
      data: [12, 19, 3, 17, 28, 24, 7],
      backgroundColor: "#65f7ac"
    }, {
      label: 'statistics-2',
      data: [30, 29, 5, 5, 20, 3, 10],
      backgroundColor: "#066333"
    }]
  }
});
        </script>
         <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>