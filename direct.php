<?php
include 'conn.php';
include 'desk.php';
include 'snackbar.php';
if (!isset($_GET['client'])) header('location:friends');
if (isset($_GET['is_group'])) header('location:group?cent=' . $_GET['is_group']); // ! File made, implementation left
// * Check if someone is not trying to inject
// ? Concept: Check if $_GET['client'] can be converted to int
$testint = 0;
try {
    // ! SQL INJECTION PREVENTION METHODS
    $testint = intval($_GET['client']);
    if ($testint < 1) die('User not found');
    $find = $conn->query("SELECT id FROM users WHERE id = " . $testint . ";");
    if ($find && $find->num_rows == 0) die('User not found');
    if ($testint == intval($config['id'])) die('That\'s your id. Can only talk to friends');
} catch (\Throwable $t) {
    die('Operation cancelled by server');
}
// * Check if user is friend with client
$chk = $conn->query("SELECT * FROM friends WHERE friend_x = " . $config['id'] . " OR friend_y = " . $config['id'] . " AND friend_x = " . $_GET['client'] . " OR friend_y = " . $_GET['client']);
if ($chk) if (!$chk->num_rows > 0) die("You are not friends with " . $_GET['client']);
else;
else die("Server error, please try again later");
$tar = $_GET['client'];
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">

    <title>Direct</title>
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <style>
        body {
            margin: 10px;
        }

        body.bg-dark {
            color: white;
        }

        body.bg-white {
            color: black;
        }

        #snackbar {
            z-index: 2000000000000;
        }

        div.p-3 {
            margin: 5px;
        }

        div.p-3:nth-last-child(1) {
            margin-bottom: 20%;
        }

        @-webkit-keyframes fadein {
            from {
                bottom: 0;
                opacity: 0;
            }

            to {
                bottom: 30px;
                opacity: 1;
            }
        }

        @keyframes fadein {
            from {
                bottom: 0;
                opacity: 0;
            }

            to {
                bottom: 30px;
                opacity: 1;
            }
        }

        @-webkit-keyframes fadeout {
            from {
                bottom: 30px;
                opacity: 1;
            }

            to {
                bottom: 0;
                opacity: 0;
            }
        }

        @keyframes fadeout {
            from {
                bottom: 30px;
                opacity: 1;
            }

            to {
                bottom: 0;
                opacity: 0;
            }
        }
    </style>
</head>

<body class="bg-white">
    <nav class="navbar navbar-dark bg-primary shadow fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="./profile.php?profid=<?php echo $tar;?>"><?php echo get_single_query_from_id($tar, "dispname", "users") ?></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="./">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./friends.php">Go back</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Like all messages</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-expanded="false">
                            Send files
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="#" data-toggle="modal" data-target="#__image_upload">Image</a></li>
                            <li><a class="dropdown-item" href="#" data-toggle="modal" data-target="#__video_upload">Video</a></li>
                            <li><a class="dropdown-item" href="#">Other</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <br><br><br>
    <!-- Image Modal -->
    <div class="modal fade" id="__image_upload" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Image</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form onsubmit="event.preventDefault();__upload_msg(document.getElementById('__img_file_main').files[0])">
                    <div class="modal-body">
                        <div class="form-file form-file-lg mb-3">
                            <input type="file" class="form-file-input" id="__img_file_main" onchange="$('#__if_label').text(this.files[0].name.split('.')[0], 'image')" accept="image/*">
                            <label class="form-file-label" for="__img_file_main">
                                <span class="form-file-text" id="__if_label">Choose file...</span>
                                <span class="form-file-button">Browse</span>
                            </label>
                            <br><br>
                            <div class="progress" id="__img_file_progress" style="display: none;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar"
                                    style="width: 99%;" aria-valuenow="99" aria-valuemin="0" aria-valuemax="100">
                                    Uploading
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="__img_close">Close</button>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Video Modal -->
    <div class="modal fade" id="__video_upload" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Video</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form onsubmit="event.preventDefault();__upload_msg(document.getElementById('__video_file_main').files[0])">
                    <div class="modal-body">
                        <div class="form-file form-file-lg mb-3">
                            <input type="file" class="form-file-input" id="__video_file_main" onchange="$('#__if_v_label').text(this.files[0].name.split('.')[0], 'video')" accept="video/*">
                            <label class="form-file-label" for="__video_file_main">
                                <span class="form-file-text" id="__if_v_label">Choose file...</span>
                                <span class="form-file-button">Browse</span>
                            </label>
                            <br><br>
                            <div class="progress" id="__video_file_progress" style="display: none;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar"
                                    style="width: 99%;" aria-valuenow="99" aria-valuemin="0" aria-valuemax="100">
                                    Uploading
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" id="__vid_close">Close</button>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="__messages_section">

    </div>
    <nav class="navbar navbar-dark bg-light fixed-bottom">
        <form onsubmit="event.preventDefault();__deliver_message(document.getElementById('__tar_message').value, <?php echo $tar; ?>)" class="container-fluid">
            <div class="input-group">
                <input autofocus autocapitalize="off" autocomplete="off" id="__tar_message" type="text" class="form-control" placeholder="Type here">
                <button type="submit" class="input-group-text btn btn-primary">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-cursor" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M14.082 2.182a.5.5 0 0 1 .103.557L8.528 15.467a.5.5 0 0 1-.917-.007L5.57 10.694.803 8.652a.5.5 0 0 1-.006-.916l12.728-5.657a.5.5 0 0 1 .556.103zM2.25 8.184l3.897 1.67a.5.5 0 0 1 .262.263l1.67 3.897L12.743 3.52 2.25 8.184z" />
                    </svg>
                </button>
            </div>
        </form>
    </nav>
    <script>
        let marr = [];
        let months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        let canScroll = 0;
        setInterval(() => {
            window.onscroll = ev => canScroll = 1;
            if ((window.innerHeight + window.scrollY) >= document.body.scrollHeight) canScroll = 0;
            if (!canScroll) window.scrollTo(0, document.body.scrollHeight);
        }, 600)
        let __deliver_message = (msg, that) => {
            if (typeof that !== 'number' && that >= 1) return 0;
            // msg = msg.replace(/'/g, '&apos;').replace(/</, '&lt;').replace(/>/, '&gt;').replace(/"/g, '&quot;');
            // console.log(msg);
            $.ajax({
                method: 'POST',
                url: './deliver.php',
                data: 'data=' + msg + '&tar=' + that,
                success: (data) => {
                    switch (data) {
                        case '<no_param_set>':
                            snack_back("Unknown error, please try again later");
                            break;
                        case '<success>':
                            document.getElementById('__tar_message').value = '';
                            break;
                        case '<error>':
                            snack_back("Server error, please try again later");
                            break;
                        default:
                            snack_back("Unknown error, please try again later");
                            break;
                    }
                }
            });
        };
        let __get_messages = async () => {
            try {
                await $.ajax({
                    method: 'POST',
                    url: './get_messages.php',
                    data: 'fid=' + <?php echo $tar; ?>,
                    success: (data) => {
                        switch (data) {
                            case '<error>':
                                return 0;
                                break;
                            case '<no_params_set>':
                                return 0;
                                break;
                            default:
                                if (JSON.parse(data) != []) {
                                    if (JSON.parse(data) != marr) marr = JSON.parse(data);
                                }
                                document.getElementById('__messages_section').innerHTML = '';
                                let __count = 0;
                                marr.map(x => {
                                    __count++;
                                    if (x['from_id'] == <?php echo $config['id']; ?>) // * You sent the text
                                    {
                                        document.getElementById('__messages_section').innerHTML += `
                                            <div id="${x['id']}" class='shadow border border-success p-3 float-right' style='width:60%;max-width:60%;display:block;border-radius:25px;${__count % 2 != 0 ? "border-bottom-right-radius:0px" : "border-top-right-radius:0px"};'>
                                                <h5>${x['text_content']}</h5>
                                                <small>
                                                    ${new Date(x['sent_on']).getDate()} ${months[new Date(x['sent_on']).getMonth()]}, ${new Date(x['sent_on']).getHours()}:${new Date(x['sent_on']).getMinutes() >= 10 ? new Date(x['sent_on']).getMinutes() : "0"+new Date(x['sent_on']).getMinutes()}
                                                </small>
                                                ${x['seen'] == 1 ? '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check2-all" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.354 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/><path d="M6.25 8.043l-.896-.897a.5.5 0 1 0-.708.708l.897.896.707-.707zm1 2.414l.896.897a.5.5 0 0 0 .708 0l7-7a.5.5 0 0 0-.708-.708L8.5 10.293l-.543-.543-.707.707z"/></svg>' : ""}
                                                ${x['liked'] == 1 ? 
                                                '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-suit-heart-fill" fill="red" xmlns="http://www.w3.org/2000/svg"><path d="M4 1c2.21 0 4 1.755 4 3.92C8 2.755 9.79 1 12 1s4 1.755 4 3.92c0 3.263-3.234 4.414-7.608 9.608a.513.513 0 0 1-.784 0C3.234 9.334 0 8.183 0 4.92 0 2.755 1.79 1 4 1z"/></svg>'
                                                :
                                                ''
                                                }
                                            </div>
                                        `;
                                    } else { // ? They sent the text
                                        document.getElementById('__messages_section').innerHTML += `
                                            <div id="${x['id']}" class='shadow border border-primary p-3 float-left' style='width:60%;max-width:60%;display:block;border-radius:25px;${__count % 2 != 0 ? "border-bottom-left-radius:0px" : "border-top-left-radius:0px"}'>
                                                <h5>${x['text_content']}</h5>
                                                <small>
                                                    ${new Date(x['sent_on']).getDate()} ${months[new Date(x['sent_on']).getMonth()]}, ${new Date(x['sent_on']).getHours()}:${new Date(x['sent_on']).getMinutes() >= 10 ? new Date(x['sent_on']).getMinutes() : "0"+new Date(x['sent_on']).getMinutes()}
                                                    ${x['liked'] == 1 ? 
                                                    "<button onclick='__dislike_msg("+x['id']+")' style='color:red;background:none;border:none' class='p-2 rounded-pill'><svg width=\"1em\" height=\"1em\" viewBox=\"0 0 16 16\" class=\"bi bi-suit-heart\" fill=\"red\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M8 6.236l.894-1.789c.222-.443.607-1.08 1.152-1.595C10.582 2.345 11.224 2 12 2c1.676 0 3 1.326 3 2.92 0 1.211-.554 2.066-1.868 3.37-.337.334-.721.695-1.146 1.093C10.878 10.423 9.5 11.717 8 13.447c-1.5-1.73-2.878-3.024-3.986-4.064-.425-.398-.81-.76-1.146-1.093C1.554 6.986 1 6.131 1 4.92 1 3.326 2.324 2 4 2c.776 0 1.418.345 1.954.852.545.515.93 1.152 1.152 1.595L8 6.236zm.392 8.292a.513.513 0 0 1-.784 0c-1.601-1.902-3.05-3.262-4.243-4.381C1.3 8.208 0 6.989 0 4.92 0 2.755 1.79 1 4 1c1.6 0 2.719 1.05 3.404 2.008.26.365.458.716.596.992a7.55 7.55 0 0 1 .596-.992C9.281 2.049 10.4 1 12 1c2.21 0 4 1.755 4 3.92 0 2.069-1.3 3.288-3.365 5.227-1.193 1.12-2.642 2.48-4.243 4.38z\"/></svg></button>"
                                                    :
                                                    "<button onclick='__like_msg("+x['id']+")' style='color:red;background:none;border:none' class='p-2 rounded-circle'><svg width=\"1em\" height=\"1em\" viewBox=\"0 0 16 16\" class=\"bi bi-suit-heart\" fill=\"currentColor\" xmlns=\"http://www.w3.org/2000/svg\"><path fill-rule=\"evenodd\" d=\"M8 6.236l.894-1.789c.222-.443.607-1.08 1.152-1.595C10.582 2.345 11.224 2 12 2c1.676 0 3 1.326 3 2.92 0 1.211-.554 2.066-1.868 3.37-.337.334-.721.695-1.146 1.093C10.878 10.423 9.5 11.717 8 13.447c-1.5-1.73-2.878-3.024-3.986-4.064-.425-.398-.81-.76-1.146-1.093C1.554 6.986 1 6.131 1 4.92 1 3.326 2.324 2 4 2c.776 0 1.418.345 1.954.852.545.515.93 1.152 1.152 1.595L8 6.236zm.392 8.292a.513.513 0 0 1-.784 0c-1.601-1.902-3.05-3.262-4.243-4.381C1.3 8.208 0 6.989 0 4.92 0 2.755 1.79 1 4 1c1.6 0 2.719 1.05 3.404 2.008.26.365.458.716.596.992a7.55 7.55 0 0 1 .596-.992C9.281 2.049 10.4 1 12 1c2.21 0 4 1.755 4 3.92 0 2.069-1.3 3.288-3.365 5.227-1.193 1.12-2.642 2.48-4.243 4.38z\"/></svg></button>"
                                                    }
                                                </small>
                                            </div>`;
                                        if (x['seen'] == 0) __send_mark_as_read_request(x['id']);
                                    }
                                });
                                // document.getElementById('__messages_section').innerHTML += '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
                                break;
                        }
                    }
                })
            } catch (e) {

            }
        };
        let __send_mark_as_read_request = (id) => {
            $.ajax({
                method: 'POST',
                url: './mark_as_read.php',
                data: 'mid=' + id,
                success: (data) => {
                    switch (data) {
                        case '<error>':
                            __send_mark_as_read_request(id);
                            break;
                        case '<success>':
                            // document.getElementById(id).innerHTML += `<small><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check2-all" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.354 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/><path d="M6.25 8.043l-.896-.897a.5.5 0 1 0-.708.708l.897.896.707-.707zm1 2.414l.896.897a.5.5 0 0 0 .708 0l7-7a.5.5 0 0 0-.708-.708L8.5 10.293l-.543-.543-.707.707z"/></svg></small>`;
                            break;
                        case '<no_param_set>':
                            // * pass
                            break;
                        default:
                            __send_mark_as_read_request(id)
                            // * Do nothing for now
                            break;
                    }
                }
            })
        }
        let __like_msg = (id) => {
            if (typeof id !== 'number' && id >= 1) return 0;
            $.ajax({
                method: 'POST',
                url: './lmsg.php',
                data: 'uid=' + id,
                success: (data) => {
                    switch (data) {
                        case '<no_params_set>':
                            return 0;
                            break;
                        case '<success>':
                            // * pass
                            break;
                        case '<error>':
                            snack_back("Internal server error");
                            break;
                        default:
                            snack_back("Couldn't like message");
                            break;
                    }
                    // console.log(data);
                }
            })
        }
        let __dislike_msg = (id) => {
            if (typeof id !== 'number' && id >= 1) return 0;
            $.ajax({
                method: 'POST',
                url: './dlmsg.php',
                data: 'uid=' + id,
                success: (data) => {
                    switch (data) {
                        case '<no_params_set>':
                            return 0;
                            break;
                        case '<success>':
                            // * pass
                            break;
                        case '<error>':
                            snack_back("Internal server error");
                            break;
                        default:
                            snack_back("Couldn't like message");
                            break;
                    }
                    // console.log(data);
                }
            })
        }
        let __upload_msg = async(fname, fsec) => {
            const fd = new FormData();
            const fs = fname;
            fd.append('file', fs);
            fd.append('client', <?php echo $_GET['client'];?>);
            if (fsec == 'image') $('#__img_file_progress').css('display', 'block');
            else if (fsec == 'video') $('#__video_file_progress').css('display', 'block');
            await $.ajax({
                method: 'POST',
                url: 'uploadmsg',
                data: fd,
                contentType: false,
                processData: false,
                success: (data)=>{
                    switch(data){
                        case '<error>':
                            snack_back('Error sending file');
                        break;
                        case '<success>':
                            // * Do nothing for now
                            if (fsec == 'image'){
                                $('#__img_file_progress').css('display', 'none');
                                $('#__img_close').click();
                            } else if (fsec == 'video'){
                                $('#__video_file_progress').css('display', 'none');
                                $('#__vid_close').click();
                            }
                        break;
                        case '<no_params_set>':
                            snack_back('No files set');
                        break;
                        default:
                            snack_back('Error sending file');
                        break;
                    }
                    // console.log(data);
                }
            })
        }
        setInterval(__get_messages, 600);
    </script>
    <!-- Optional JavaScript -->
    <!-- Popper.js first, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
</body>

</html>