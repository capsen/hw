<?php
include "userlist.php";
?>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/global.css">
    </head>
    <body>
        <div class="container">
            <div class="info">

            </div>
            <div class="main">
                <h1>
                    Python 2022 Assignment Submission Portal
                </h1>
                <?php if($userlist[strtoupper($_GET['token'])]) : ?>
                    <p>
                        Hi <?php echo $userlist[strtoupper($_GET['token'])] ?>, Please select the week for your homework then drag the file into the dropzone.
                    </p>
                    <select class="form-select" onchange="selectWeek(this)" name="week" id="week" aria-label="Default select example">
                        <option selected>Please select the week</option>
                        <option value="2">Week Two</option>
                        <option value="3">Week Three</option>
                        <option value="4">Week Four</option>
                        <option value="5">Week Five</option>
                        <option value="6">Week Six</option>
                        <option value="7">Week Seven</option>
                        <option value="8">Week Eight</option>
                        <option value="9">Week Nine</option>
                        <option value="10">Week Ten</option>
                    </select>
                    <div id="upload-container" style="display:none">
                        <div id="uploads"></div>
                        <div class="dropzone" id="dropzone">Drop files here</div>
                    </div>
                <hr>
                <div class="file-list">
                    <h2>
                        File list
                    </h2>
                    <?php
    function listFolderFiles($dir){
        $ffs = scandir($dir);

        unset($ffs[array_search('.', $ffs, true)]);
        unset($ffs[array_search('..', $ffs, true)]);

        // prevent empty ordered elements
        if (count($ffs) < 1)
            return;

        echo '<ul>';
        foreach($ffs as $ff){
            echo '<li>'.$ff;
            if(is_dir($dir.'/'.$ff)) listFolderFiles($dir.'/'.$ff);
            echo '</li>';
        }
        echo '</ul>';
    }

    listFolderFiles('./uploads/'.$userlist[strtoupper($_GET['token'])]);
                    ?>
                </div>
                <?php else : ?>
                    <p>
                        Please enter token!
                    </p>
                    <form>
                        <input type="text" name="token" />
                        <button>
                            Submit
                        </button>
                    </form>
                <?php endif; ?>

            </div>
        </div>


        <script>
            function selectWeek(week){
                let uploadContainer = document.getElementById('upload-container');
                if(+(week.value)>0){
                    uploadContainer.style.display='block';
                }else{
                    uploadContainer.style.display='none';
                }
            }

            (function(){
                var dropzone = document.getElementById('dropzone');

                var displayUploads = function(data){
                    var uploads = document.getElementById('uploads'),
                        anchor,
                        x;

                    for(x = 0; x < data.length; x++){
                        anchor = document.createElement('a');
                        anchor.href = data[x].file;
                        anchor.innerText = data[x].name;
                        uploads.appendChild(anchor);

                    }
                }
                var upload = function(files){
                    var formData = new FormData(),
                        xhr = new XMLHttpRequest(),
                        x;

                    for(x = 0; x<files.length; x++){
                        formData.append('file[]',files[x]);
                    }
                    xhr.onload = function(){
                        var data = JSON.parse(this.responseText);
                        console.log(data);
                        displayUploads(data);
                        if(data.length>0){
                            alert('File Uploaded Successfully');
                            location.reload();
                            }else{
                                alert('file upload error, each file can not be more than 2MB.');
                            }
                    }
                    xhr.open('post','upload.php?token=<?php echo $_GET['token']?>&week='+document.getElementById('week').value);
                    xhr.send(formData);
                    xhr.onerror = () => {alert('File upload failed');};
                }
                dropzone.ondrop = function(e){
                    e.preventDefault();
                    this.className = "dropzone";
                    upload(e.dataTransfer.files);
                }
                dropzone.ondragover = function(){
                    this.className = 'dropzone dragover';
                    return false;
                }
                dropzone.ondragleave = function(){
                    this.className = 'dropzone';
                    return false;
                }
            }());
        </script>
    </body>
</html>