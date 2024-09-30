<?php
//The socket functions described here are part of an extension to PHP which must be enabled at compile time by giving the --enable-sockets option to configure.
//Add extension=php_sockets.dll in php.ini and extension=sockets
//user defined rule 4
//super admin rule 14
//normal user 0

use App\Helpers\App\ZKLibrary;

include "zklibrary.php";
echo 'Library Loaded</br>';
$zk = new ZKLibrary('192.168.100.70', 4370, 'UDP');
echo 'Requesting for connection</br>';
$zk->connect();
echo 'Connected</br>';
$zk->disableDevice();
echo 'disabling device</br>';
$users = $zk->getUser();
?>
<br /><br />
<table width="100%" border="1" cellspacing="0" cellpadding="0" style="border-collapse:collapse;">
<thead>
  <tr>
    <td width="25">No</td>
    <td>User ID</td>
    <td>ID</td>
    <td>Name</td>
    <td>Role</td>
    <td>Password</td>
  </tr>
</thead>

<tbody>
<?php
$no = 0;
foreach($users as $key=>$user)
{
  $no++;
?>

  <tr>
    <td align="right"><?php echo $no;?></td>
    <td><?php echo $key;?></td>
    <td><?php echo $user[0];?></td>
    <td><?php echo $user[1];?></td>
    <td><?php echo $user[2];?></td>
    <td><?php echo $user[3];?></td>
  </tr>

<?php
}
?>

</tbody>
</table>
<?php

$zk->enableDevice();
$zk->disconnect();

?>
