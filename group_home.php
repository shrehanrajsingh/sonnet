<?php
include 'conn.php';
include 'desk.php';
include 'snackbar.php';
if (!isset($_GET['group_id'])) die('No group to join');
// ! Security measures
$gid = $_GET['group_id'];
// ? Is user a member of the group
$chk_1 = $conn->query("SELECT * FROM groups WHERE id = $gid;");
if ($chk_1 && $chk_1->num_rows > 0) if (!in_array($config['id'], json_decode($chk_1->fetch_assoc()['members']))) die('You are not a part of this group');
// ? Is user trying to sql inject, if yes, only get the number and ignore the query
$gid = (int) $gid;
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">

    <title><?php echo get_single_query_from_id($gid, "group_name", "groups"); ?></title>
    <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <style>
        body {
            margin: 25px;
            margin-bottom: 1em;
            /* max-height: 100vh; */
        }
        div.msg-count:nth-last-child(2){
            margin-bottom: 20%;
        }
    </style>
</head>
<script>
    $(document).ready(_=>{
        $('#__main_frame').css('margin-top', $('#__group_salutation').height()+64+'px');
    });
</script>

<body class="bg-light">
    <div>
        <!-- // * Salutation -->
        <h1 class="display-4 fixed-top bg-primary p-3 shadow" id="__group_salutation" style="color: white; display: block"><?php echo get_single_query_from_id($gid, "group_name", "groups"); ?>
            <button class="btn float-right p-2" style="font-size: 20px;" data-toggle="modal" data-target="#exampleModal">
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-info-square-fill" fill="white" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.93 4.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM8 5.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2z" />
                </svg>
            </button>
            <button class="btn float-right p-2" style="font-size: 20px;" onclick="location.href='./group.php'">
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-house-door-fill" fill="white" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.5 10.995V14.5a.5.5 0 0 1-.5.5H2a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .146-.354l6-6a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 .146.354v7a.5.5 0 0 1-.5.5h-4a.5.5 0 0 1-.5-.5V11c0-.25-.25-.5-.5-.5H7c-.25 0-.5.25-.5.495z"/>
                    <path fill-rule="evenodd" d="M13 2.5V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"/>
                </svg>
            </button>
        </h1>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Group info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card" style="width: 100%; font-size: 120%">
                            <h5 class="p-3 display-4" style="font-size: 20px;">
                                <?php
                                echo get_single_query_from_id($gid, "group_desc", "groups");
                                ?>
                                <h6 style="padding: 0px 0px 1rem 1rem;">
                                    <?php echo count(json_decode($conn->query("SELECT group_texts FROM groups WHERE id = $gid;")->fetch_assoc()['group_texts'])); ?> chat(s)
                                </h6>
                            </h5>
                            <div class="card-header">
                                <img src="<?php echo get_profile_from_id(get_single_query_from_id($gid, "founder", "groups")); ?>" alt width="40" height="40" style="border-radius: 50%;">
                                <?php
                                echo get_single_query_from_id(get_single_query_from_id($gid, "founder", "groups"), "uname", "users");
                                if (in_array($config['id'], json_decode(get_single_query_from_id($gid, "admins", "groups")))) {
                                ?>
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-award-fill float-right" fill="#28a745" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 0l1.669.864 1.858.282.842 1.68 1.337 1.32L13.4 6l.306 1.854-1.337 1.32-.842 1.68-1.858.282L8 12l-1.669-.864-1.858-.282-.842-1.68-1.337-1.32L2.6 6l-.306-1.854 1.337-1.32.842-1.68L6.331.864 8 0z" />
                                        <path d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1 4 11.794z" />
                                    </svg>
                                <?php
                                }
                                ?>
                            </div>
                            <ul class="list-group list-group-flush">
                                <?php
                                $get_mems = json_decode(get_single_query_from_id($gid, "members", "groups"));
                                foreach ($get_mems as $key) {
                                    if ($key == $config['id']) continue;
                                ?>
                                    <li class="list-group-item">
                                        <img src="<?php echo get_profile_from_id($key); ?>" alt width="40" height="40" style="border-radius: 50%;">
                                        <?php
                                        echo get_single_query_from_id($key, "uname", "users");
                                        if (in_array($key, json_decode(get_single_query_from_id($gid, "admins", "groups")))) {
                                        ?>
                                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-award-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M8 0l1.669.864 1.858.282.842 1.68 1.337 1.32L13.4 6l.306 1.854-1.337 1.32-.842 1.68-1.858.282L8 12l-1.669-.864-1.858-.282-.842-1.68-1.337-1.32L2.6 6l-.306-1.854 1.337-1.32.842-1.68L6.331.864 8 0z" />
                                                <path d="M4 11.794V16l4-1 4 1v-4.206l-2.018.306L8 13.126 6.018 12.1 4 11.794z" />
                                            </svg>
                                        <?php
                                        }
                                        ?>
                                    </li>
                                <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="__main_frame">
        
    </div>
    <div id="__form_main" class="fixed-bottom p-3">
        <button onclick="window.scrollTo(0, document.body.scrollHeight);" class="btn btn-primary rounded-circle p-2 mb-2 float-right" id="__float_move_btn" style="display: none;">
            <svg width="2em" height="2em" viewBox="0 0 16 16" class="bi bi-chevron-down" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
            </svg>
        </button>
        <form onsubmit="event.preventDefault(); __form_submit($('#__text_state').val())" id="__f_m">
            <div class="form-group input-group">
                <input autofocus autocomplete="off" type="text" class="form-control" name="__text_state" id="__text_state" placeholder="Type here...">
                <button type="submit" class="input-group-text btn btn-primary">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-cursor-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M14.082 2.182a.5.5 0 0 1 .103.557L8.528 15.467a.5.5 0 0 1-.917-.007L5.57 10.694.803 8.652a.5.5 0 0 1-.006-.916l12.728-5.657a.5.5 0 0 1 .556.103z" />
                    </svg>
                </button>
                <button class="input-group-text btn btn-success" data-toggle="modal" data-target="#__group_upload_modal">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-cloud-arrow-up-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M8 2a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 6.095 0 7.555 0 9.318 0 11.366 1.708 13 3.781 13h8.906C14.502 13 16 11.57 16 9.773c0-1.636-1.242-2.969-2.834-3.194C12.923 3.999 10.69 2 8 2zm2.354 5.146l-2-2a.5.5 0 0 0-.708 0l-2 2a.5.5 0 1 0 .708.708L7.5 6.707V10.5a.5.5 0 0 0 1 0V6.707l1.146 1.147a.5.5 0 0 0 .708-.708z"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
    <!-- Upload Modal  -->
    <div class="modal fade" id="__group_upload_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Upload- </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <form onsubmit="event.preventDefault();__send_group_post(document.getElementById('customFileLg').files[0], $('#__label_post').val())">
                <div class="modal-body">
                        <div class="form-file form-file-lg mb-3">
                        <input required type="file" class="form-file-input" id="customFileLg" onchange="document.getElementById('customFileLgLab').innerHTML = this.files[0].name.split('.')[0]">
                        <label class="form-file-label" for="customFileLg">
                            <span class="form-file-text" id="customFileLgLab">Choose file...</span>
                            <span class="form-file-button">Browse</span>
                        </label>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="__label_post" id="__label_post" placeholder="Enter text (Optional)">
                        </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
            </div>
        </div>
        </div>
    </div>
    <script>
        let months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        let canScroll = 0;
        setInterval(() => {
            window.onscroll = ev => canScroll = 1;
            if ((window.innerHeight + window.scrollY) >= document.body.scrollHeight) canScroll = 0;
            if (!canScroll) {
                window.scrollTo(0, document.body.scrollHeight);
                // $("#__float_move_btn").css('display', 'none');
                $("#__float_move_btn").fadeOut();
            } else {
                // $("#__float_move_btn").css('display', 'block');
                $("#__float_move_btn").fadeIn();
            }
        }, 300)
        let __form_submit = async (txt_sub) => {
            if (txt_sub.length === 0) return 0;
            txt_sub = txt_sub.replace(/'/g, '&apos;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            const msgConfig = {
                from: <?php echo intval($config['id']); ?>,
                to: <?php echo $gid; ?>,
                content: txt_sub,
                txt_sent_at: Date.now(),
                txt_likes: 0,
                txt_seen_by: []
            };
            await $.ajax({
                method: 'POST',
                url: './sgmsg.php',
                data: 'config=' + JSON.stringify(msgConfig),
                success: (data) => {
                    switch (data) {
                        case '<error>':
                            break;
                        case '<no_params_set>':
                            return 0;
                            break;
                        case '<success>':
                            document.getElementById('__text_state').value = '';
                            break;
                        default:
                            document.getElementById('__text_state').value = '';
                            break;
                    }
                }
            });
        }
        let __g_array = [];
        let __further_proc = async() => {
            // ! Remove array values with empty array
            __g_array.conf = __g_array.conf.filter(e => e.length !== 0);
            // console.log(__g_array.conf);
            let td = document.getElementById("__main_frame").innerHTML;
            // document.getElementById("__main_frame").innerHTML = '';
            for (let i = document.getElementsByClassName('msg-count').length; i < __g_array.conf.length; i++) {
                const el = __g_array.conf[i];
                if (__g_array.conf.length == document.getElementsByClassName('msg-count').length) continue; // ? No new messages(Date
                let imgSource = ".).temp/group_main.jpg";
                await $.ajax({
                    method: 'POST',
                    url: './get_prof.php',
                    data: 'id='+el['from'],
                    success: (data) => {
                        imgSource = data;
                    }
                });
                // document.getElementById("__main_frame").innerHTML = td;
                let __j = "";
                let __i = __g_array.conf.length + 16;
                while(__i) {
                    __j += "<br>";
                    __i--;
                }
                if (el.from != <?php echo $config['id']; ?>) 
                document.getElementById("__main_frame").innerHTML += `
                    <div class='border border-primary p-3 float-left  msg-count' style='margin-bottom:15px ;width:60%;max-width:60%;display:block;border-radius:25px;'>
                        <h5 style='font-size: 20px; margin-bottom: 12.5px' class='display-1'>${el.content}</h5>
                        <small class='float-right'>
                            ${isToday(new Date(Date(el['txt_sent_at']))) ? new Date(Date(el['txt_sent_at'])).getHours() + ":" + (new Date(Date(el['txt_sent_at'])).getMinutes() >= 10 ? new Date(Date(el['txt_sent_at'])).getMinutes() : "0"+new Date(Date(el['txt_sent_at'])).getMinutes()): (new Date(Date(el['txt_sent_at'])).getDate() + " " + months[new Date(Date(el['txt_sent_at'])).getMonth()] + ", " + new Date(Date(el['txt_sent_at'])).getFullYear())}
                            ${
                                el['txt_seen_by'].length == (<?php echo get_single_query_from_id($gid, "members", "groups"); ?>).length?
                                '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check2-all" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.354 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/><path d="M6.25 8.043l-.896-.897a.5.5 0 1 0-.708.708l.897.896.707-.707zm1 2.414l.896.897a.5.5 0 0 0 .708 0l7-7a.5.5 0 0 0-.708-.708L8.5 10.293l-.543-.543-.707.707z"/></svg>'
                                :''
                            }
                        </small>
                        <img src='${imgSource}' class='float-left' width='25' height='25' style='border-radius: 50%' alt />
                    </div>
                `;
                else
                document.getElementById("__main_frame").innerHTML += `
                    <div class='border border-success p-3 float-right msg-count' style='margin-bottom:15px ;width:60%;max-width:60%;display:block;border-radius:25px;'>
                        <h5 style='font-size: 20px' class='display-1'>${el.content}</h5>
                        <small>
                            ${isToday(new Date(Date(el['txt_sent_at']))) ? new Date(Date(el['txt_sent_at'])).getHours() + ":" + (new Date(Date(el['txt_sent_at'])).getMinutes() >= 10 ? new Date(Date(el['txt_sent_at'])).getMinutes() : "0"+new Date(Date(el['txt_sent_at'])).getMinutes()): (new Date(Date(el['txt_sent_at'])).getDate() + " " + months[new Date(Date(el['txt_sent_at'])).getMonth()] + ", " + new Date(Date(el['txt_sent_at'])).getFullYear())}
                            ${
                                el['txt_seen_by'].length == (<?php echo get_single_query_from_id($gid, "members", "groups"); ?>).length?
                                '<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check2-all" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.354 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/><path d="M6.25 8.043l-.896-.897a.5.5 0 1 0-.708.708l.897.896.707-.707zm1 2.414l.896.897a.5.5 0 0 0 .708 0l7-7a.5.5 0 0 0-.708-.708L8.5 10.293l-.543-.543-.707.707z"/></svg>'
                                :''
                            }
                        </small>
                        <img src='<?php echo get_profile_from_id($config['id']) ?>' class='float-right' width='25' height='25' style='border-radius: 50%' alt />
                    </div>
                    ${i == __g_array.conf.length - 1 ? "<div class='float-right'>" + __j + "</div>" : ""}
                `;
                __mark_group_messages_as_read(i);
                // if (i == __g_array.conf.length - 1) document.getElementById("__main_frame").innerHTML += "<div class='float-left' style='height: 30px'></div>";
            }
        };
        let __mark_group_messages_as_read = (id) => {
            $.ajax({
                method: 'POST',
                url: './mark_group_messages_as_read.php',
                data: 'gid='+<?php echo $gid ?>+'&txid='+id,
                success: (data) => {
                    if (data == '<error>') __mark_group_messages_as_read(id);
                    return 0;
                }
            });
        };
        let __send_group_post = async(f, c) => {
            var fdata = new FormData();
            fdata.append('file', f);
            fdata.append('cap', c);
            fdata.append('gid', <?php echo $gid; ?>)
            await $.ajax({
                method: 'POST',
                url: './uploadGroupSpecFile.php',
                cache: false,
                contentType: false,
                processData: false,
                data: fdata,
                success: (data) => {
                    switch(data){
                        case '<success>':
                        break;
                        case '<error>':
                        break;
                        default:
                        break;
                    }
                    console.log(data);
                }
            });
        };
        let __get_texts = async() => {
            await $.ajax({
                method: 'POST',
                url: './ggmsg.php',
                data: 'gid='+<?php echo $gid; ?>,
                success: async(data) => {
                    data = JSON.parse(data);
                    if (!data.status) return 0;
                    if (__g_array != data){
                        __g_array = data;
                        await __further_proc();
                    } else return 0;
                }
            });
        };
        setInterval(__get_texts, 1000);
    </script>
    <!-- Optional JavaScript -->
    <!-- Popper.js first, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
</body>

</html>