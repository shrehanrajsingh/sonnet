<?php
// header('location:./home.php');
$request = $_SERVER['REQUEST_URI'];
$tok = "/"; # Change to '/' in development
$request = explode("?", $request);
$request = $request[0];

switch ($request) {
    case $tok :
        require __DIR__ . '/home.php';
    break;
    case '' :
        require __DIR__ . '/home.php';
    break;
    case $tok . 'login':
        require __DIR__ . '/login.php';
    break;
    case $tok . 'signup':
        require __DIR__ . '/signup.php';
    break;
    case $tok . 'search':
        require __DIR__ . '/search.php';
    break;
    case $tok . 'activity':
        require __DIR__ . '/activity.php';
    break;
    case $tok  . 'like_post':
        require __DIR__ . '/like_post.php';
    break;
    case $tok . 'delete_post':
        require __DIR__ . '/delete_post.php';
    break;
    case $tok . 'comment_append':
        require __DIR__ . '/add_comment.php';
    break;
    case $tok . 'like_comment':
        require __DIR__ . '/like_comment.php';
    break;
    case $tok . 'direct':
        require __DIR__ . '/direct.php';
    break;
    case $tok . 'friends':
        require __DIR__ . '/friends.php';
    break;
    case $tok . 'deliver':
        require __DIR__ . '/deliver.php';
    break;
    case $tok . 'group':
        require __DIR__ . '/group.php';
    break;
    case $tok . 'getmsg':
        require __DIR__ . '/get_messages.php';
    break;
    case $tok . 'maread':
        require __DIR__ . '/mark_as_read.php';
    break;
    case $tok . 'notifyMe':
        require __DIR__ . '/get_notification.php';
    break;
    case $tok . 'friend_backend':
        require __DIR__ . '/tf.php';
    break;
    case $tok . 'likemsg':
        require __DIR__ . '/lmsg.php';
    break;
    case $tok . 'dislikemsg':
        require __DIR__ . '/dlmsg.php';
    break;
    case $tok . 'getmsghome':
        require __DIR__ . '/get_direct_notifications.php';
    break;
    case $tok . 'uploadmsg':
        require __DIR__ . '/upload_msg.php';
    break;
    case $tok . 'profile':
        require __DIR__ . '/profile.php';
    break;
    case $tok . 'posts':
        require __DIR__ . '/posts.php';
    break;
    case $tok . 'new_group':
        require __DIR__ . '/group_wizard.php';
    break;
    case $tok . 'form_group':
        require __DIR__ . '/group_exec.php';
    break;
    case $tok . 'club':
        require __DIR__ . '/group_home.php';
    break;
    case $tok . 'send_group_msg':
        require __DIR__ . '/sgmsg.php';
    break;
    case $tok . 'get_group_msg':
        require __DIR__ . '/ggmsg.php';
    break;
    case $tok . 'gprof_native':
        require __DIR__ . '/get_prof.php';
    break;
    case $tok . 'get_group_messages_notification':
        require __DIR__ . '/get_group_notifications.php';
    break;
    case $tok . 'mark_group_messages_as_read':
        require __DIR__ . '/mark_group_messages_as_read.php';
    break;
    case $tok . 'uploadGroupSpec':
        require __DIR__ . '/uploadGroupSpecFile.php';
    break;
    default:
        http_response_code(404);
        echo "Error";
    break;
}

?>