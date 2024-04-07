<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet"href="css/scrollbar.css">
    <script src="https://kit.fontawesome.com/470d815d8e.js"crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">

    <title>Document</title>
</head>
<body  class="bg-white">
<main class="h-screen mx-auto flex flex-col justify-center items-center sm:flex-row overflow-y-auto">
    <img class="lg:block hidden object-fit h-3/4 lg:h-[90%] w-full sm:w-auto md:w-[720px] border
    border-black" src="assets/reposync%20signup%20image-01.jpg" alt="Cookie Image">
    <div class="card w-full h-full  sm:w-96 lg:h-[90%] bg-transparent text-neutral-content border-none md:border rounded-none">
        <div class="card-body flex flex-col justify-center items-center lg:border-black lg:border">
            <h2 class="text-2xl text-black font-bold">Sign Up</h2>
            <section class="lg:overflow-y-auto lg:h-[23rem] scroll-smooth">
                <form method="post" id="signup-form">
                    <div class="flex flex-col gap-2 justify-center text-black ">
                        <label class="font-bold text-sm">Select Role</label>
                        <select class="w-full h-8 rounded bg-slate-50 " name="user_role">
                            <option value="no_input">-</option>
                            <option value="Student">Student</option>
                            <option value="Adviser">Adviser</option>
                        </select>

                        <label class="font-bold text-sm">First Name</label>
                        <input required name="first_name" class="h-8 bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3  leading-tight
                            focus:outline-none focus:shadow-outline"  type="text" placeholder="Enter you first name">
                        <label class=" font-bold text-sm">Last Name</label>
                        <input required name="last_name" class="h-8 bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 leading-tight
                            focus:outline-none focus:shadow-outline"  type="text" placeholder="Enter your last name">
                        <label class="font-bold text-sm">Email</label>
                        <input required name="email" class="h-8 bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3  leading-tight
                            focus:outline-none focus:shadow-outline" type="email" placeholder="yourmail@example.com">
                        <label class="h-8 font-bold text-sm">Password</label>
                        <input required name="password" class="bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 leading-tight
                            focus:outline-none focus:shadow-outline"  type="password" placeholder="Enter your password">
                        <input required name="conf_password" class="h-8 bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 leading-tight
                            focus:outline-none focus:shadow-outline"  type="password" placeholder="Confirm your password">

                        <label class="font-bold text-sm">School ID</label>
                        <input required name="sch_id" class="h-8 bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3  leading-tight
                            focus:outline-none focus:shadow-outline" type="text" placeholder="0000-00000">
                        <div>
                            <label class="font-bold text-sm">Select Program</label>
                            <select required name="program" class="w-full h-8 rounded bg-slate-50 ">
                                <option>-</option>
                                <option>BSIT</option>
                                <option>BSBM</option>
                                <option>BSCpE</option>
                                <option>BSCS</option>
                            </select>
                        </div>
                        <label class="font-bold text-sm">Upload ID</label>
                        <input name="file_sch_id" required type="file" class="block w-full text-sm text-black
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-full file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-violet-50 file:text-slate-400
                                  hover:file:bg-slate-200 transition-all
                                "/>
                    </div>
                </form>
            </section>
            <div class="card-actions flex justify-center mt-4">
                <button id="form-submit" class="btn btn-success btn-outline mr-2 h-10 p-3 w-20" >Submit</button>
            </div>
        </div>
    </div>
</main>
<script src="js/main.js"></script>
<script>
    document.getElementById("form-submit").addEventListener("click", function() {
        let firstName = document.getElementsByName("first_name")[0].value.trim();
        let lastName = document.getElementsByName("last_name")[0].value.trim();
        let email = document.getElementsByName("email")[0].value.trim();
        let password = document.getElementsByName("password")[0].value.trim();
        let confirmPassword = document.getElementsByName("conf_password")[0].value.trim();
        let schId = document.getElementsByName("sch_id")[0].value.trim();
        let program = document.getElementsByName("program")[0].value.trim();
        let file = document.getElementsByName("file_sch_id")[0].files[0];

        if (firstName === '' || lastName === '' || email === '' || password === '' || confirmPassword === '' || schId === '' || program === '' || !file) {
            alert("Please fill in all fields and select a file.");
            return;
        }

        // Checking if passwords match
        if (password !== confirmPassword) {
            alert("Passwords do not match.");
            return;
        }

        document.getElementById("signup-form").submit();
    });

    // jQuery form submission listener
    $(document).on('submit', '.cart_list', function (event) {
        event.preventDefault();
        $.ajax({
            url: 'ajax.php?action=update_cart',
            method: 'POST',
            data:$(this).serialize(),
            success: function (resp) {
                if (resp == 1){
                    updateTotal();
                }
                updateCart();
            }
        });
    });


</script>

</body>
</html>