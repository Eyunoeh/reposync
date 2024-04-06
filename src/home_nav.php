<header class="bg-green-700 text-white sticky top-0 z-20">
    <section class="max-w-6xl mx-auto p-2 flex lg:justify-between justify-center items-center">
        <div class="flex items-center justify-between border-accent gap-1">
            <div class="avatar">
                <div class="w-16 rounded">
                    <img src="assets/cvsulogo-removebg-preview.png" />
                </div>
            </div>
            <div class="text-center font-bold  text-3xl">
                <h1><a href="#" id="homeLink">REPOSYNC</a></h1>
            </div>
        </div>

        <button id="mobile-open-button" class="text-3xl lg:hidden absolute left-0 focus:outline-none" onclick="toggleNav()">
            &#9776;
        </button>
            <nav class="lg:inline-flex hidden space-x-4 text-lg/normal lg:text-xl  justify-between items-center" aria-label="main">

                <a href="#" id="narrativesLink" class="font-bold hover:cursor-pointer hover:opacity-75">Narrative Reports</a>
                <a href="#" id="announcementLink" class="font-bold hover:cursor-pointer hover:opacity-75">Announcement</a>
                <a href="#" id="announcementLink" class="font-bold hover:cursor-pointer hover:opacity-75">Journal</a> <!-- if login student-->
                <!--<a href="#" id="announcementLink" class="font-bold hover:cursor-pointer hover:opacity-75">Sign Up</a> start -->

                <div class="dropdown dropdown-bottom dropdown-end flex  items-center gap-2">

                    <div class="avatar"  role="button" tabindex="0" >
                        <div class="w-10 rounded-full">
                            <img src="https://daisyui.com/images/stock/photo-1534528741775-53994a69daeb.jpg" />
                        </div>
                    </div>
                    <ul tabindex="0" class="absolute bg-slate-100 text-black right-0 dropdown-content z-[1] menu p-2 shadow  rounded w-52">
                        <li><a>Dashboard</a></li>
                        <li><a>Logout</a></li>
                    </ul>
                </div>

                <!--<div class="dropdown dropdown-bottom dropdown-end flex  items-center gap-2">
                    <div tabindex="0" role="button" class="text-lg/normal font-bold btn btn-success">Login<i class="fa-solid fa-caret-down"></i></div>
                    <ul tabindex="0" class="absolute bg-slate-100 text-black right-0 dropdown-content z-[1] menu p-2 shadow  rounded w-52">
                        <li><a href="login.php">Student or Adviser</a></li>
                        <li><a href="adminLogin.php">Admin</a></li>
                    </ul>
                </div> start-->

             </nav>

         </div>
     </section>
 </header>
 <div id="mySidenav" class=" lg:hidden fixed top-0 left-0 h-full w-1/2 bg-gray-600  text-white bg-opacity-70 transition-transform transform translate-x-[-100%] z-30">
     <div onclick="closeNav()" class="rounded h-6 w-6 foc absolute active:border  right-0">
         <a href="#" class="grid place-items-center mt-1"><i class="fas fa-times"></i></a>
     </div>
     <section class="lg:hidden mt-10">
         <div class="flex flex-col text-center">
             <div id="side-narrativesLink" class="h-16 hover:bg-gray-200  transition-all rounded hover:text-black">
                 <h1 class="mt-6 text-3xl font-bold hover:opacity-90 "><a href="#"  >Narrative Report</a></h1>
                 <hr class="mx-auto  bg-black  w-[80%]">
             </div>
             <div id="side-announcementLink" class="h-16 hover:bg-gray-200 transition-all rounded hover:text-black">
                 <h1 class="text-3xl font-bold mt-6 hover:opacity-90 "><a href="#" >Announcement</a></h1>
                 <hr class="mx-auto bg-black  w-[80%]">
             </div>
             <div class="h-16 hover:bg-gray-200 transition-all rounded">
                 <h1 class="text-3xl font-bold mt-6 hover:opacity-90 "><a href="index.php?page=login">Login</a></h1>
                 <hr class="mx-auto bg-black  w-[80%]">
             </div>
         </div>
     </section>
 </div>
<script>


</script>
