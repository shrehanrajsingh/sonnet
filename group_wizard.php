<?php
include 'conn.php';
include 'desk.php';
include 'snackbar.php';
?>
<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">

  <title>New Group</title>
  <style>
    body {
      margin: 25px;
    }

    ::-webkit-scrollbar {
      width: 5px;
      background-color: #0d6efd;
    }
  </style>
  <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
  <script>
    let fullGroup = {
      name: "",
      desc: "",
      members: [],
    };
  </script>
</head>

<body class="bg-light">
  <h1 class="display-4">New Group</h1>
  <hr>
  <form onsubmit="__execGroupRequest(event)">
    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">Group name</label>
      <div class="col-sm-10">
        <input pattern="[^'\x22]+" title="Quotes are not allowed" required type="text" class="form-control" onchange="fullGroup.name = this.value">
      </div>
    </div>
    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">Group description</label>
      <div class="col-sm-10">
        <textarea pattern="[^'\x22]+" title="Quotes are not allowed" required name="__group_desc" id="__group_desc" cols="20" rows="5" class="form-control" onchange="fullGroup.desc = this.value"></textarea>
      </div>
    </div>
    <div class="mb-3 row">
      <label class="col-sm-2 col-form-label">Add members</label>
      <div class="col-sm-10">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#__friend_modal">
          Add members
        </button>

        <!-- Modal  -->
        <div class="modal fade" id="__friend_modal" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Add members</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="card" style="width: 100%;">
                  <ul class="list-group list-group-flush" style="max-height: 30%; overflow-y: scroll">
                    <?php
                    $farr = [];
                    $get_friends = $conn->query("SELECT * FROM friends WHERE friend_x = " . $config['id'] . " OR friend_y = " . $config['id'] . ";");
                    if ($get_friends) {
                      if ($get_friends->num_rows > 0) {
                        while ($g = $get_friends->fetch_assoc()) {
                          $fn = $g['friend_x'] == $config['id'] ? $g['friend_y'] : $g['friend_x'];
                          $farr[] = $fn;
                        }
                      }
                    }
                    foreach ($farr as $key) {
                    ?>
                      <li class="list-group-item" onclick="__appendMember(<?php echo $key; ?>, this)">
                        <img src="<?php echo get_profile_from_id($key); ?>" width="40" height="40" style="border-radius:50%" alt>
                        <?php echo get_single_query_from_id($key, "uname", "users"); ?>
                      </li>
                    <?php
                    }
                    ?>
                  </ul>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Done</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <br><br>
    <div class="mb-3 row">
      <button type="submit" class="btn btn-primary">Create</button>
    </div>
  </form>
  <button class="btn btn-primary rounded-circle" onclick="location.replace('./')" style="position: fixed; bottom: 25px; right: 25px;">
    <h1>
        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-house-door-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path d="M6.5 10.995V14.5a.5.5 0 0 1-.5.5H2a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .146-.354l6-6a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 .146.354v7a.5.5 0 0 1-.5.5h-4a.5.5 0 0 1-.5-.5V11c0-.25-.25-.5-.5-.5H7c-.25 0-.5.25-.5.495z"/>
            <path fill-rule="evenodd" d="M13 2.5V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"/>
        </svg>
    </h1>
  </button>
  <script>
    let __appendMember = (id, el) => {
      if (!fullGroup.members.includes(id)) {
        fullGroup.members.push(id);
        el.innerHTML += "✔";
      } else {
        fullGroup.members = fullGroup.members.filter(e => e !== id);
        if (el.innerHTML.substr(el.innerHTML.length - 1) == "✔") el.innerHTML = el.innerHTML.substr(0, el.innerHTML.length - 1);
      }
    };
    let __execGroupRequest = async(e) => {
      e.preventDefault();
      if (fullGroup.name == "" || fullGroup.desc == "" || fullGroup.members.length == 0) {
        snack_back("Fields cannot be empty");
        return 0;
      }
      fullGroup.name = fullGroup.name.replace(/'/g, '&amp;apos;').replace(/"/g, '&amp;quot;').replace(/</g, '&amp;lt;').replace(/>/g, '&amp;gt;');
      fullGroup.desc = fullGroup.desc.replace(/'/g, '&amp;apos;').replace(/"/g, '&amp;quot;').replace(/</g, '&amp;lt;').replace(/>/g, '&amp;gt;');
      const res = JSON.stringify(fullGroup);
      // console.log(res);
      // return 0;
      await $.ajax({
        method: 'POST',
        url: './group_exec.php',
        data: 'obj='+res,
        success: (data) => {
          switch(data){
            case '<success>':
              snack_back("Group successfully created");
            break;
            case '<error>':
              snack_back("Error creating group");
            break;
            case '<no_params_set>':
              return 0;
            break;
            default:
              snack_back("Unknown error occurred");
            break;
          }
          console.log(data);
        }
      });
    }
  </script>
  <!-- Optional JavaScript -->
  <!-- Popper.js first, then Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
</body>

</html>