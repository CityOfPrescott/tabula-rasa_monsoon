<?php
/**
 * Template Name: Safety Handbook
 *
 */

get_header(); ?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
	<div class="hfeed content old_portal">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<img src="/_i/icons/<?php echo get_post_meta($post->ID, 'title_icon', true); ?>" class="h2title" />
			<h2><?php the_title(); ?></h2>
			<hr />
			<div class="entry">
				<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>

				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

			</div>
<h4>Safety Manual</h4>
<ol start=0>
<li><a href="/_d/handbook/safety_intro.pdf">Introduction and Policies
	<ul style=" list-style-type: none;";>
	<li>Letter from City Manager</li>
	<li>Table of Contents</li></ul>
	<ol type="a"></a>
	<li><a href="/_d/handbook/safety_emperresp.pdf">Employer Responsibility</li></a>
	<li><a href="/_d/handbook/safety_empyeresp.pdf">Safety Rules for All Employees</li></a>
	<li><a href="/_d/handbook/Reporting_Procedures.pdf">Reporting Procedures- On the job injuries</li></a>
	<li><a href="/_d/handbook/safety_workcomp.pdf">Workers Compensation Policy</li></a>
	<li><a href="/_d/handbook/safety_reporting_property.pdf">Reporting Procedures- Damage/injury to City or private property/citizen</li></a>
	</ol>
</li>
<br />
<li><a href="/_d/handbook/safety_bloodborne.pdf">Bloodborne Pathogens
<ul style=" list-style-type: none;";>
<li>Written Exposure Control Program</li>
<li>29 CFR 1910.1030</li>
<li>Administration Regulation on Bloodborne Pathogens</li>
</ul></li></a>

<br />
<li>Confined Space Entry
<ul style=" list-style-type: none;";>
<li>Written Safety Program</li>
<li>29 CFR 1910.146</li>
</ul></li>

<br />
<li><a href="/_d/handbook/safety_ergonomics.pdf">Ergonomics
<ul style=" list-style-type: none;";>
<li>Written Safety Program</li>
</ul></li>

<br />
<li><a href="/_d/handbook/safety_fall.pdf">Fall Protection, Prevention and Ladder
<ul style=" list-style-type: none;";>
<li>Written Safety Program</li>
<li>29 CFR 1910 Subpart D</li>
<li>29 CFR 1926 Subpart M</li>
</ul></li></a>

<br />
<li><a href="/_d/handbook/safety_hazcom.pdf">Hazard Communication
<ul style=" list-style-type: none;";>
<li>Written Safety Program</li>
<li>29 CFR 1910.1200</li>
</ul></li></a>

<br />
<li><a href="/_d/handbook/safety_hot.pdf">Hot Weather Awareness
<ul style=" list-style-type: none;";>
<li>Written Safety Program</li>
<li>Section 5(a)(1) and 5(a)(2) General Duty Clause</li>
</ul></li></a>

<br />
<li><a href="/_d/handbook/Lockout_Tagout.pdf">Lockout-Tagout
<ul style=" list-style-type: none;";>
<li>Written Safety Program</li>
<li>29 CFR 1910.147</li></li>
<ol type="a">
<li>Lockout-Tagout Wastewater Treatment Plan</li></ol>
</ul></li></a>

<br />
<li><a href="/_d/handbook/safety_fire.pdf">Fire Prevention
<ul style=" list-style-type: none;";>
<li>Written Safety Program</li>
<li>29 CFR 1910.38</li>
</ul></li></a>

<br />
<li><a href="/_d/handbook/safety_protective.pdf">Personal Protective Equipment
<ul style=" list-style-type: none;";>
<li>Written Safety Program</li>
<li>29 CFR 1910.132</li>
</ul></li></a>

<br />
<li>Powered Industrial Trucks
<ul style=" list-style-type: none;";>
<li>Written Safety Program</li>
<li>29 CFR 1910.178</li>
</ul></li>

<br />
<li>Respiratory Protection
<ul style=" list-style-type: none;";>
<li>Written Safety Program</li>
<li>29 CFR 1910.134</li>
</ul></li>

<br />
<li><a href="/_d/handbook/Tool_Safety.pdf">Tool Safety
<ul style=" list-style-type: none;";>
<li>Written Safety Program</li>
<li>29 CFR 1926 Subpart I</li>
</ul></li></a>

<br />
<li><a href="/_d/handbook/Trench_Excavation.pdf">Trench and Excavation
<ul style=" list-style-type: none;";>
<li>Written Safety Program</li>
<li>29 CFR 1926 Subpart P</li>
</ul></li></a>

<br />
<li><a href="/_d/handbook/Electrical_Safety.pdf">Electrical Safety
<ul style=" list-style-type: none;";>
<li>Written Safety Program</li>
<li>29 CFR 1910.331 - 1910.339</li>
</ul></li></a>

<br />
<li><a href="/_d/handbook/Hearing_Conservation.pdf">Hearing Conservation
<ul style=" list-style-type: none;";>
<li>Written Safety Program</li>
<li>29 CFR 1910.95</li>
</ul></li></a>

<br />
<li><a href="/_d/handbook/Vehicle_Safety.pdf">Vehicle Safety
<ul style=" list-style-type: none;";>
<li>Written Safety Program</li>
<li>Section 5(a)(1) and 5(a)(2) General Duty Clause</li>
</ul></li></a>

<br />
<li>Violence Prevention
<ul style=" list-style-type: none;";>
<li>Written Safety Program</li>
<li>Section 5(a)(1) and 5(a)(2) General Duty Clause</li>
</ul></li>

<br />
<li><a href="/_d/handbook/Heavy_Equipment.pdf">Heavy Equipment Operations
<ul style=" list-style-type: none;";>
<li>Written Safety Program</li>
<li>Section 5(a)(1) and 5(a)(2) General Duty Clause</li>
</ul></li></a>

<br />
<li><a href="/_d/handbook/Aerial_Scissor_Lift.pdf">Aerial and Scissor Lift Safety

<ul style=" list-style-type: none;";>
<li>Written Safety Program</li>
<li>Section 5(a)(1) and 5(a)(2) General Duty Clause</li>
</ul></li></a>

<br />
<li><a href="/_d/handbook/Cold_Weather_Awareness.pdf">Cold Weather Awareness
<ul style=" list-style-type: none;";>
<li>Written Safety Program</li>
<li>Section 5(a)(1) and 5(a)(2) General Duty Clause</li>
</ul></li></a>


</ol>
</div> <!-- .toc -->
		<?php endwhile; endif; ?>
	<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
	</div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>