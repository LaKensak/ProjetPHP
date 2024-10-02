<?php
global $pdo;
include "../include/autoload.php";
require "../admin/config.php";

$head = '';
if (isset($_SESSION['user_id'])) {
    echo 'Bienvenue ' . $_SESSION['username'] . ' !';
} else {
    $head = <<<EOD
    <p class="text-red-500 font-bold">Vous devez vous connecter pour enregistrer un temps</p>
    EOD;
}
?>

<html>
<section class="c-space my-20">
    <?php echo $head ?>
    <div class="grid xl:grid-cols-2 xl:grid-rows-3 md:grid-cols-2 grid-cols-1 gap-5 h-full">
        <div class="col-span-1 xl:row-span-3">
            <div class="grid-container">
                <a href="../comps/chrono.php?id=Gan-11-M-Pro"><img src="../assets/GAN-11-Pro-3x3-Magnetic-Frosted-4-removebg-preview.png" alt="grid-1" class="w-full sm:h-[276px] h-fit object-contain" id="Gan-11-M-Pro"></a>
                <div><p class="grid-headtext">Gan 11 M Pro</p></div>
            </div>
        </div>
        <div class="col-span-1 xl:row-span-3">
            <div class="grid-container"><a href="../comps/chrono.php?id=MoYu-Weilong-WR-M-2020"><img src="../assets/moyu-weilong-wrm-v10-3x3-standard-removebg-preview.png"
                                                                                                     alt="grid-2"
                                                                                                     class="w-full sm:w-[276] h-fit object-contain" id="MoYu-Weilong-WR-M-2020"></a>
                <div><p class="grid-headtext">MoYu Weilong WR M 2020</p></div>
            </div>
        </div>
        <div class="col-span-1 xl:row-span-3">
            <div class="grid-container">
                <div class="rounded-3xl w-full sm:h-[326px] h-fit flex justify-center items-center">
                    <div>
                        <div style="position: relative;">
                            <div>
                                <a href="../comps/chrono.php?id=Gan-356-XS"><img src="../assets/moyu-weilong-gts3m-stickerless-removebg-preview.png"
                                                                                 class="w-full sm:h-[266px] h-fit object-contain" id="Gan-356-XS"></a>
                                <div><p class="grid-headtext">Gan 356 XS</p></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="xl:col-span-2 xl:row-span-3">
            <div class="grid-container"><a href="../comps/chrono.php?id=QiYi-Valk-3-Elite-M"><img src="../assets/shopping-removebg-preview.png" alt="grid-3"
                                                                                                  class="w-full sm:h-[266px] h-fit object-contain" id="QiYi-Valk-3-Elite-M"></a>
                <div><p class="grid-headtext">QiYi Valk 3 Elite M</p></div>
            </div>
        </div>
        <div class="xl:col-span-1 xl:row-span-4">
            <div class="grid-container">
                <a href="../comps/chrono.php?id=MoYu-WeiLong-GTS3-M"><img src="../assets/elite-m-removebg-preview.png" alt="grid-4"
                                                                          class="w-full md:h-[276px] sm:h-[276] h-fit object-cover sm:object-top" id="MoYu-WeiLong-GTS3-M"></a>
                <div><p class="grid-headtext">MoYu WeiLong GTS3 M</p></div>
            </div>
        </div>
        <div class="col-span-1 xl:row-span-2">
            <div class="grid-container">
                <a href="../comps/chrono.php?id=QiYi-MS-Magnetic"><img src="../assets/qiyi-ms-3x3-magnetic-removebg-preview.png"
                                                                       alt="grid-1"
                                                                       class="w-full sm:h-[276px] h-fit object-contain" id="QiYi-MS-Magnetic"></a>
                <div><p class="grid-headtext">QiYi MS Magnetic</p></div>
            </div>
        </div>
        <div class="col-span-1 xl:row-span-2">
            <div class="grid-container"><a href="../comps/chrono.php?id=Angstrom-Valk-3-Elite-M"><img src="../assets/AngstromValk3EliteM-removebg-preview.png"
                                                                                                      alt="grid-2"
                                                                                                      class="w-full sm:w-[276] h-fit object-contain" id="Angstrom-Valk-3-Elite-M"></a>
                <div><p class="grid-headtext">Angstrom Valk 3 Elite M</p></div>
            </div>
        </div>
        <div class="xl:col-span-1 xl:row-span-1">
            <div class="grid-container">
                <a href="../comps/chrono.php?id=YongJun-YuLong-V2-M"><img src="../assets/51WYfDjmAuL-removebg-preview.png" alt="grid-4"
                                                                          class="w-full md:h-[276px] sm:h-[276] h-fit object-cover sm:object-top" id="YongJun-YuLong-V2-M"></a>
                <div><p class="grid-headtext">YongJun YuLong V2 M</p></div>
            </div>
        </div>
        <div class="xl:col-span-3 xl:row-span-2">
            <div class="grid-container"><a href="../comps/chrono.php?id=QiYi-Warrior-S-Stickerless"><img src="../assets/51R-R4y1d9S-removebg-preview.png" alt="grid-3"
                                                                                                         class="w-full sm:h-[266px] h-fit object-contain" id="QiYi-Warrior-S-Stickerless"></a>
                <div><p class="grid-headtext">QiYi Warrior S stickerless</p></div>
            </div>
        </div>
    </div>
</section>
</html>
