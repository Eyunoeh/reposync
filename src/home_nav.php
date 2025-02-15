
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<header style="background-color: #1b651b;" class="text-white sticky top-0 z-20">
    <section class="max-w-6xl mx-auto p-2 flex lg:justify-between justify-center items-center">
        <div class="flex items-center justify-between border-accent gap-1">
            <div class="avatar">
                <div class="w-14 rounded">
                    <img class="object-scale-down" src="assets/cvsulogo-removebg-preview.png" />
                </div>
            </div>
            <div class="text-center text-l text-white font-sans">
                <h1><a href="#" id="homeLink" >CvSU-INSIGHT</a></h1>
            </div>
        </div>

        <button id="mobile-open-button" class="text-3xl lg:hidden absolute left-0 focus:outline-none" onclick="toggleNav()">
            &#9776;
        </button>
            <nav class="lg:inline-flex hidden space-x-4 text-md lg:text-xl  justify-between items-center" aria-label="main">



                <a href="#" id="announcement" class="transition-all hover:cursor-pointer
                hover:text-black hover:bg-gray-300 font-bold hover:rounded p-2">Announcement</a>
                <?php if (!isset($_SESSION['log_user_type'])):?>

                 <div class="dropdown dropdown-bottom dropdown-end flex  items-center gap-2">
                     <a href=" login.php" tabindex="0" role="button" class="focus:outline-none font-bold text-xl text-white bg-yellow-400 hover:bg-yellow-500 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-5 py-1.5 mt-2 me-2 mb-2 dark:focus:ring-yellow-900">Login</a>
                 </div>
                <?php else:?>
                    <a href="#" id="narrativesLink" class="transition-all hover:cursor-pointer
                hover:text-black hover:bg-gray-300  hover:rounded p-2 " >Narrative Reports</a>

                    <?php if ($_SESSION['log_user_type'] == 'student' && $_SESSION['log_acc_stat'] === 'active') {?>
                        <a href="#" id="reportLink" class="transition-all hover:cursor-pointer
                hover:text-black hover:bg-gray-300 hover:rounded p-2
                ">Weekly Journal</a>
                   <?php
                    }?>



                <?php
                endif;?>


               <?php if (isset($_SESSION['log_user_type'])):?>
                <div class="dropdown dropdown-bottom dropdown-end flex  items-center gap-2">
                    <div class="avatar"  role="button" tabindex="0" >
                        <div class="w-10 rounded-full">
                            <img id="profile_nav" src="userProfile/<?=$_SESSION['log_user_profileImg'] !== 'N/A' ? $_SESSION['log_user_profileImg'] : 'prof.jpg'?>" />
                        </div>
                    </div>
                   <p id="navName"  role="button" tabindex="0"
                      class=" text-xs text-slate-50 font-sans font-semibold  max-w-24 break-words"></p>
                    <ul tabindex="0" class="absolute bg-slate-100 text-black right-0 dropdown-content z-[1] menu p-2 shadow  rounded w-52">

                        <?php echo $_SESSION['log_user_type'] != 'student'  && $_SESSION['log_acc_stat'] === 'active' ? '<li><a href="dashboard.php">Dashboard</a></li>': ''?>
                        <?php echo $_SESSION['log_user_type'] == 'student'  && $_SESSION['log_acc_stat'] === 'active'? '<li><a id="accountSettings" href="#">Account Settings</a></li>': ''?>
                        <li><a href="logout.php">Logout</a></li>
                    </ul>
                </div><?php
                endif;?>


             </nav>
     </section>
 </header>
 <div id="mySidenav" class=" lg:hidden fixed top-0 left-0 h-full w-[70%] sm:w-1/2 bg-gray-600  text-white bg-opacity-70 transition-transform transform translate-x-[-100%] z-50">
     <div onclick="closeNav()" class="rounded h-6 w-6 foc absolute active:border  right-0">
         <a href="#" class="grid place-items-center mt-1"><i class="fas fa-times"></i></a>
     </div>
     <section class="lg:hidden mt-10">
         <div class="flex flex-col text-center">
             <div id="side-announcement" class="h-16 hover:bg-gray-200 transition-all rounded hover:text-black">
                 <h1 class="text-xl  mt-6 hover:opacity-90 "><a href="#" >Announcement</a></h1>
                 <hr class="mx-auto bg-black  w-[80%]">
             </div>
             <?php if (!isset($_SESSION['log_user_type'])):?>

                 <div class="h-16 hover:bg-gray-200 transition-all rounded">

                     <a  class="text-xl  mt-6 hover:opacity-90 " href="login.php">
                         <h1 class="text-xl  mt-6 hover:opacity-90 ">Login</h1>
                     </a>
                     <hr class="mx-auto bg-black  w-[80%]">
                 </div>
             <?php else:?>
                 <div id="side-narrativesLink" class="h-16 hover:bg-gray-200  transition-all rounded hover:text-black">
                     <h1 class="mt-6 text-xl  hover:opacity-90 "><a href="#"  >Narrative Report</a></h1>
                     <hr class="mx-auto  bg-black  w-[80%]">
                 </div>
                <?php if ($_SESSION['log_user_type'] === 'student') :?>


                 <div id="side-weeklyjournal" class="h-16 hover:bg-gray-200 transition-all rounded hover:text-black">
                     <h1 class="text-xl  mt-6 hover:opacity-90 "><a href="#" >Weekly Journal</a></h1>
                     <hr class="mx-auto bg-black  w-[80%]">
                 </div>
                 <?php endif;?>
                 <div class="h-16 hover:bg-gray-200 transition-all rounded">
                     <h1 class="text-xl  mt-6 hover:opacity-90 "><a href="logout.php">Logout</a></h1>
                     <hr class="mx-auto bg-black  w-[80%]">
                 </div>
             <?php endif;?>
         </div>
     </section>
 </div>
