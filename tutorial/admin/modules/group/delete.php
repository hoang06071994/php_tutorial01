<?php
if (!defined('_INCODE')) die('access denied... ');
$body = getBody();
print_r($body);
if (!empty($body['id'])) {
    $groupId = $body['id'];
    $groupDetail = getRows("SELECT id FROM `groups` WHERE id=$groupId");
    if ($groupDetail > 0) {

        $userNum = getRows("SELECT id FROM users WHERE group_id=$groupId");
        if ($userNum > 0) {
            setFlashData('msg', 'Deletion failed, because there are still members in the group');
            setFlashData('msg_type', 'danger');
        } else  {
            $deleteGroup = delete('`groups`', "id=$groupId");
            if ($deleteGroup) {
                setFlashData('msg', 'delete success');
                setFlashData('msg_type', 'success');
            } else {
                setFlashData('msg', 'He thong dang gap loi, vui long thu lai sau');
                setFlashData('msg_type', 'danger');
            }
        }
    } else {
        setFlashData('msg', 'Group not found');
        setFlashData('msg_type', 'danger');
    }
} else {
    setFlashData('msg', 'Group not found');
    setFlashData('msg_type', 'danger');
}
redirect('?module=group');