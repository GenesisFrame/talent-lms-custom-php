<?php
$output .= "<div id=\"container-courses-right-categories-left\">";
if($category_price > 0 && $talentlms_info['paypal_email']) {
	if (isset($_SESSION['talentlms_user_id'])) {
		$user = TalentLMS_User::retrieve($_SESSION['talentlms_user_id']);
		$user_courses = array();
		foreach ($user['courses'] as $c) {
			$user_courses[] = $c['id'];
		}
		foreach ($courses as $c) {
			$category_courses[] = $c['id'];
		}
		$course_diff = array_diff($user_courses, $category_courses);
		
		if(!empty($course_diff)){
			$output .= "<form class=\"form-horizontal\" method=\"post\" action=\"" . current_page_url() . "\">";
			$output .= "<input name=\"talentlms-get-category-courses\" type=\"hidden\" value=\"" . $_GET['tlcategory'] . "\">";
			$output .= "<input name=\"talentlms-category-price\" type=\"hidden\" value=\"" . $category_price . "\">";
			$output .= "<button class=\"btn\" type=\"submit\">" . __('Buy all courses in category') . ": " . $category_price . "</button>";
			$output .= "</form>";			
		}
	} else {
		$output .= "<div class=\"category-price\" id=\"talentlms-login-dialog-opener\">";
		$output .= "<a class=\"btn\" href=\"javascript:void(0);\">" . __('Login to buy all courses in category') . "</a>";
		$output .= "</div>";		
	}
}
// Categories
$categories = build_categories_tree($categories);
$output .= "<div id=\"categories-container\">";
$output .= "<fieldset>";
$output .= "<legend>" . __('Categories:') . "</legend>";
$output .= $categories;
$output .= "</fieldset>";
$output .= "</div>"; 

// Courses
$output .= "<div id=\"courses-container\">";
if ((get_option('talentlms-courses-per-page') && $numofpages > 1) && get_option('talentlms-courses-top-pagination')) {
	include (_BASEPATH_ . '/templates/pagination.php');
}
$output .= "<table class=\"table\">";
$output .= "<thead>";
$output .= "<tr>";
if(get_option('talentlms-show-course-list-thumb')){
	$output .= "<th colspan=\"2\" style=\"text-align: center\">" . __('Course') . "</th>";
} else {
	$output .= "<th>" . __('Course') . "</th>";	
}
if(get_option('talentlms-show-course-list-description')) {
	$output .= "<th style=\"text-align: center\">" . __('Description') . "</th>";
}
if(get_option('talentlms-show-course-list-price')){
	$output .= "<th style=\"text-align: center\">" . __('Price') . "</th>";
}
$output .= "</tr>";
$output .= "</thead>";
$output .= "<tbody>";
foreach ($courses as $course) {
	if(!$course['hide_from_catalog'] && $course['status'] == 'active' ){
		$output .= "<tr class=\"course-catalog-tr\">";
		
		if(get_option('talentlms-show-course-list-thumb')){
			$output .= "<td style=\"vertical-align: middle; width:100px;\">";
			$output .= "<a href=\"?tlcourse=" . $course['id'] . "\">";
			if (strstr($course['avatar'], 'unknown_small.png')) {
				$output .= "<img title=\"" . $course['name'] . "\" alt=\"" . $course['name'] . "\" src=\"http://" . $course['avatar'] . "\">";
			} else {
				$output .= "<img title=\"" . $course['name'] . "\" alt=\"" . $course['name'] . "\" src=\"" . $course['avatar'] . "\">";
			}
			$output .= "</a>";
			$output .= "</td>";
		}
		
		$output .= "<td>";
		$course_name = ($course['code']) ? $course['name'] . ' (' . $course['code'] . ')' : $course['name'];
		$output .= "<a href=\"?tlcourse=" . $course['id'] . "\">" . $course_name . "</a>";
		$output .= "</td>";
		
		if(get_option('talentlms-show-course-list-description')) {
			$output .= "<td>";
			if(get_option('talentlms-show-course-list-description-limit')){
				$output .= "<p style=\"text-align:justify;\">" . tl_limit_words($course['description'], get_option('talentlms-show-course-list-description-limit')) . "</p>";
			} else {
				$output .= "<p style=\"text-align:justify;\">" . $course['description'] . "</p>";
			}
			$output .= "</td>";
		}

		if(get_option('talentlms-show-course-list-price')){
			$output .= "<td width=\"10%\" style=\"text-align: center; vertical-align: middle;\">";
			$output .= (preg_replace("/\D+/", "", html_entity_decode($course['price'])) == 0) ? '-' : $course['price'];
			$output .= "</td>";			
		}
				
		$output .= "</tr>";
	}
}
$output .= "</tbody>";
$output .= "</table>";
if ((get_option('talentlms-courses-per-page') && $numofpages > 1) && get_option('talentlms-courses-bottom-pagination')) {
	include (_BASEPATH_ . '/templates/pagination.php');
}
$output .= "</div>";


$output .= "<div class=\"clear\"></div>";
include (_BASEPATH_ . '/templates/talentlms-login-dialog.php');
$output .= "</div>";
?>