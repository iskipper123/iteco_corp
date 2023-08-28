<?php 
	$login = $_SESSION["login"];
	$user = DB::getObject();
	$result_set100 = $user->getUserByLogin($login);
	$row100 = $result_set100->fetch_assoc();
	$_SESSION["id"] = $row100["id"];
?>
<ul class="navbar-nav">
    <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="javascript:void(0)">
            <span class="mr-2 d-none d-lg-inline text-gray-600 small"> <?=$row100["name"]?></span>
        </a>
    </li>
    <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle" href="\lib/logout.php">
        <i class="fas fa-sign-out-alt"></i>
        </a>
    </li>
</ul>
