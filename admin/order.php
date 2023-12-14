<?php include("header.php"); ?>

  
   <div class="content">
      <div class="container-fluid">
         <div class="row">
            <div class="col-md-12">
               <div class="card p-card">
                  <div class="header">
                     <div class="row">
                        <div class="col-md-9 col-xs-7">
                           <h4 class="title">Orders</h4>
                        </div>
                        <div class="col-md-3 col-xs-5 text-right">
                           <a onclick="return confirm('Are you sure you want to delete all records?');" href="order.php?clear=order" class="btn btn-primary btn-fill">Delete All</a>
                        </div>
                     </div>
                  </div>
                  <!-- Header End -->

                  <div class="table-responsive table-full-width">
                     <table id="ot1" class="table table-bordered table-striped table-responsive">
                        <thead>
                           <th>Order ID</th>
                           <th>Order Time</th>
                           <th>Customer Name</th>
                           <th>Discount</th>
                           <th>Order Total</th>
                           <th>Action</th>
                        </thead>
                        <tbody>

                        </tbody>
                     </table>
                  </div>
                  
               </div>  
            </div>
         </div>
      </div>
   </div>


<?php include("footer.php"); ?>