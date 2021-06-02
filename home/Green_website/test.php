<?php
require_once("./helper/function.php");
$web_id = 2;
$url = 'trang-chu';

if (isset($_GET['url'])) {
    $url = $_GET['url'];
}

if (isset($_GET['page'])) {
    echo '<h1>' . $_GET['page'] . '</h1>';
}

if (strpos($url, "/") != false) {
    header('location: ./');
}

if (isset($_GET['name'])) {
    $name = $_GET['name'];
    $title1 = $_GET['title'];
    $breadcrumbs = $_GET['breadcrumbs'];
    $name_breadcrumbs = $_GET['nameBreadcrumbs'];
    $postNews = $_GET['postNews'];
}

$post_detail = get_data_row("SELECT post_detail.ptd_id, post_detail.ptd_text FROM post_detail, post WHERE post.ptd_id = post_detail.ptd_id AND post.post_rewrite_name = '$name'");
$post_content = get_data_rows("SELECT * FROM post_detail");

$category = get_data_row("SELECT cmp_background, bgt_type, cmp_active, cmp_name, cmp_rewrite_name, cmp_id, post_type_id FROM categories_multi_parent WHERE cmp_rewrite_name = '$url' AND web_id = $web_id");

/********** LEFT TITLE  **********/

$arr_left_title = get_data_rows("SELECT * FROM post_type");

/********** HOUSE **********/

$arr_house = get_data_rows("SELECT * FROM categories_multi_parent WHERE cmp_parent_id = 18 AND web_id = $web_id");

/********** CONTACT **********/

$arr_contact = get_data_rows("SELECT * FROM configuration WHERE web_id = $web_id");

?>

<!DOCTYPE html>
<html>

<head>
    <title> <?php echo $title1 ?> </title>
    <? include("./includes/inc_head.php"); ?>
    <link rel="stylesheet" href="../Green_website/resource/css/news.css">
</head>

<body>
    <!--------------- HEADER --------------->

    <? include('./includes/inc_header.php') ?>

    <!--------------- CONTENT --------------->

    <div class="container">
        <div class="row">
            <div class="col-lg-3 order-lg-1 order-md-2 order-sm-2 order-2">
                <div class="news-left">
                    <div class="news-left-title">
                        <?php
                        foreach ($arr_left_title as $key => $title) {
                            if ($title['post_type_show'] == 7) {
                                echo '
                                    <a href="#" target="_self">
                                        ' . $title['post_type_title'] . '
                                    </a>';
                            }
                        }
                        ?>
                    </div>

                    <div class="news-left-content">
                        <ul class="list-post">
                            <?php
                            foreach ($arr_house as $key => $house) {
                                if ($house['cmp_active'] == 1) {
                                    echo '
                                            <li>
                                                <a href="#" target="_self">
                                                    <i class="fas fa-chevron-right"></i>
                                                    ' . $house['cmp_name'] . '
                                                </a>
                                            </li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>

                <div class="news-left">
                    <?php
                    foreach ($arr_left_title as $key => $title) {
                        if ($title['post_type_show'] == 5) {
                            echo '
                                    <div class="news-left-title">
                                        <a href="#" target="_self">
                                            ' . $title['post_type_title'] . '
                                        </a>
                                    </div>';

                            $left_id = $title['post_type_id'];
                        }
                    }
                    ?>

                    <div class="news-left-content">
                        <ul class="list-news">
                            <?php
                            $arr_news_post = get_data_rows("SELECT * FROM post WHERE post_type_id = $left_id ORDER BY post_datetime_create DESC LIMIT 6");
                            foreach ($arr_news_post as $key => $news_post) {
                                if ($news_post['post_image_background'] != '') {

                                    $date = $news_post['post_datetime_create'];
                                    $myDate = date("d-m-Y", strtotime($date));

                                    echo '
                                        <li>
                                            <div class="list-news-container">
                                                <div class="list-news-image">
                                                    <a href="news.php?name=' . $news_post['post_rewrite_name'] . '&title=' . $news_post['post_title'] . '&breadcrumbs=' . $category['cmp_rewrite_name'] . '&nameBreadcrumbs=' . $category['cmp_name'] . '&postNews=' . $news_post['ptd_id'] . '" target="_self">
                                                        <img src="../../data/image/images/Web-2/' . $news_post['post_image_background'] . '" alt="list news image">
                                                    </a>
                                                </div>

                                                <div class="list-news-content">
                                                    <a href="news.php?name=' . $news_post['post_rewrite_name'] . '&title=' . $news_post['post_title'] . '&breadcrumbs=' . $category['cmp_rewrite_name'] . '&nameBreadcrumbs=' . $category['cmp_name'] . '&postNews=' . $news_post['ptd_id'] . '" target="_self">
                                                        ' . $news_post['post_title'] . '
                                                    </a>

                                                    <div class="list-news-date">
                                                        <p>' . $myDate . '</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>

                <div class="news-left">
                    <?php
                    foreach ($arr_left_title as $key => $title) {
                        if ($title['post_type_show'] == 9) {
                            echo '
                                    <div class="news-left-title">
                                        <a href="#" target="_self">
                                            ' . $title['post_type_title'] . '
                                        </a>
                                    </div>';
                        }
                    }
                    ?>

                    <div class="news-left-content">
                        <?php
                        foreach ($arr_left_title as $key => $title) {
                            if ($title['post_type_show'] == 10) {
                                echo '
                                    <div class="hotline-title">' . $title['post_type_title'] . '</div>';
                            }
                        }
                        ?>

                        <div class="hotline-logo">
                            <img src="../Green_website/resource/images/hotline3.png" alt="hotline">
                        </div>

                        <?php
                        foreach ($arr_contact as $key => $contact) {
                            echo '
                                <div class="hotline-contact">
                                    <a href="tel:' . $contact['con_hotline'] . '" target="_self">
                                        <i class="fas fa-phone-alt"></i>
                                        ' . $contact['con_hotline'] . '
                                    </a>
                                </div>
                                
                                <div class="hotline-contact">
                                    <a href="#" target="_self">
                                        <i class="far fa-envelope"></i>
                                        ' . $contact['con_admin_email'] . '
                                    </a>
                                </div>';
                        }
                        ?>

                        <div class="hotline-social-media">
                            <table>
                                <tr>
                                    <?php
                                    foreach ($arr_contact as $key => $contact) {
                                        echo '
                                                <td>
                                                    <a href="' . $contact['con_link_fb'] . '" target="_self">
                                                        <i class="fab fa-facebook-square fb"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="' . $contact['con_link_twitter'] . '" target="_self">
                                                        <i class="fab fa-twitter-square tw"></i>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="' . $contact['con_link_insta'] . '" target="_self">
                                                        <i class="fab fa-instagram-square ins"></i>
                                                    </a>
                                                </td>';
                                    }
                                    ?>
                                </tr>
                            </table>
                        </div>

                        <div class="hotline-support">
                            <?php
                            foreach ($arr_left_title as $key => $title) {
                                if ($title['post_type_show'] == 11) {
                                    echo '
                                        <div class="hotline-title">' . $title['post_type_title'] . '</div>';
                                }
                            }
                            ?>

                            <?php
                            foreach ($arr_contact as $key => $contact) {
                                echo '
                                    <div class="hotline-contact">
                                        <a href="tel:' . $contact['con_hotline_hotro_kythuat'] . '" target="_self" style="font-size: 22px">
                                            <i class="fas fa-mobile-alt"></i>
                                            ' . $contact['con_hotline_hotro_kythuat'] . '
                                        </a>
                                    </div>

                                    <div class="hotline-contact">
                                        <a href="#" target="_self">
                                            <i class="far fa-envelope"></i>
                                            ' . $contact['con_admin_email'] . '
                                        </a>
                                    </div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php $bread_topic = get_data_rows("SELECT * FROM categories_multi_parent WHERE web_id = $web_id"); ?>
            <div class="news-right col-lg-9 order-lg-2 order-md-1 order-sm-1 order-1">
                <div class="news-right-container">
                    <div class="breadcrumb">
                        <?php
                        foreach ($bread_topic as $key => $bread) {
                            if ($bread['cmp_rewrite_name'] == 'trang-chu') {
                                echo '
                                        <a href="' . $bread['cmp_rewrite_name'] . '" target="_self">' . $bread['cmp_name'] . '</a>';
                            }

                            if ($bread['cmp_rewrite_name'] == 'dich-vu') {
                                echo '
                                        <span class="navigation-pipe">
                                            <i class="fas fa-chevron-right"></i>
                                        </span>
                                        <a href="' . $bread['cmp_rewrite_name'] . '" target="_self">' . $bread['cmp_name'] . '</a>';
                            }
                        } ?>

                        <span class="navigation-pipe">
                            <i class="fas fa-chevron-right"></i>
                        </span>

                        <a href="#" target="_self"><?php echo $title1 ?> </a>
                    </div>
                    <div class="news-right-content">
                        <p> Hello </p>
                    </div>

                    <div class="contact-footer">
                        <h5>Mời liên hệ:</h5>
                        <?php
                        foreach ($arr_contact as $key => $call) {
                            echo '
                                    <p> Địa chỉ: ' . $call['con_address'] . '</p>
                                    <p> Hotline: ' . $call['con_hotline'] . '</p>
                                ';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--------------- FOOTER --------------->

    <? include('./includes/inc_footer.php') ?>

    <? include('./includes/inc_foot.php') ?>
</body>

</html>