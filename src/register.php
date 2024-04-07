<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/output.css">
    <link rel="stylesheet" href="css/scrollbar.css">
    <script src="https://kit.fontawesome.com/470d815d8e.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">

    <title>Document</title>
</head>
<body  class="bg-white">
<main class="h-screen mx-auto flex flex-col justify-center items-center sm:flex-row overflow-y-auto">
    <img class="lg:block hidden object-fit h-3/4 lg:h-[90%] w-full sm:w-auto md:w-[720px] border border-black" src="assets/reposync%20signup%20image-01.jpg" alt="Cookie Image">
    <div class="card w-full h-full sm:w-96 lg:h-[90%] bg-transparent text-neutral-content border-none md:border rounded-none">
        <div class="card-body flex flex-col justify-center items-center lg:border-black lg:border">
            <h2 class="text-2xl text-black font-bold">Sign Up</h2>
            <section class="lg:overflow-y-auto lg:h-[28rem] scroll-smooth">
                <form method="post" id="signup-form">
                    <div class="flex flex-col gap-2 justify-center text-black">
                        <label class="font-bold text-sm">Select Role</label>
                        <select class="w-full h-8 rounded bg-slate-50" name="user_role">
                            <option value="no_input">-</option>
                            <option value="Student">Student</option>
                            <option value="Adviser">Adviser</option>
                        </select>

                        <div class="flex flex-col gap-2">
                            <label class="font-bold text-sm">First Name</label>
                            <input required name="first_name" class="h-8 bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" type="text" placeholder="Enter your first name">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-bold text-sm">Last Name</label>
                            <input required name="last_name" class="h-8 bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" type="text" placeholder="Enter your last name">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-bold text-sm">Email</label>
                            <input required name="email" class="h-8 bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" type="email" placeholder="yourmail@example.com">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-bold text-sm">Password</label>
                            <input required name="password" class="h-8 bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" type="password" placeholder="Enter your password">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-bold text-sm">Confirm Password</label>
                            <input required name="conf_password" class="h-8 bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" type="password" placeholder="Confirm your password">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-bold text-sm">School ID</label>
                            <input required name="sch_id" class="h-8 bg-slate-50 shadow appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" type="text" placeholder="0000-00000">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-bold text-sm">Select Program</label>
                            <select required name="program" class="w-full h-8 rounded bg-slate-50">
                                <option>-</option>
                                <option>BSIT</option>
                                <option>BSBM</option>
                                <option>BSCpE</option>
                                <option>BSCS</option>
                            </select>
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="font-bold text-sm">Upload ID</label>
                            <input name="file_sch_id" required type="file" class="block w-full text-sm text-black file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-slate-400 hover:file:bg-slate-200 transition-all" />
                        </div>
                    </div>
                    <div class="card-actions flex justify-center mt-4">
                        <button id="form-submit" class="btn btn-success btn-outline mr-2 h-10 p-3 w-20">Submit</button>
                    </div>
                </form>
            </section>
        </div>
    </div>
</main>


<script>
    document.getElementById("form-submit").addEventListener("click", function(event) {
        event.preventDefault();
        let formData = new FormData(document.getElementById("signup-form"));

        let isValid = true;
        formData.forEach(function(value, key) {
            // Skip file inputs
            if (key === 'file_sch_id') return;
            if (!value.trim()) {
                isValid = false;
                return;
            }
        });

        // Check if file input is empty
        let fileInput = document.querySelector('input[name="file_sch_id"]');
        if (!fileInput.files || fileInput.files.length === 0) {
            isValid = false;
        }

        if (!isValid) {
            alert("Please fill in all fields and select a file.");
            return;
        }

        if (formData.get('password') !== formData.get('conf_password')) {
            alert("Passwords do not match.");
            return;
        }

        $.ajax({
            url: 'ajax.php?action=signUp',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response == 1){
                    // Show modal success
                }
                else {
                    console.log(response);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });

</script>




<script src="js/main.js"></script>
</body>
</html>