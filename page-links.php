<?php
/**
 * Template Name: Links
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

<div id="column_1">
<h3>City and County</h3>
<ul>
<li><a href="http://www.chinoaz.net/index.shtml">Chino Valley</a></li>
<li><a href="http://www.codepublishing.com/AZ/Prescott/">City Code</a></li>
<li><a href="http://www.prescott-az.gov/leadership/meetings/">City Council Agendas</a></li>
<li><a href="http://www.prescott-az.gov/">City of Prescott</a></li>
<li><a href="http://www.cympo.com">CYMPO</a></li>
<li><a href="http://prescott-az.gov/services/building/ldc.php">Land Development Code</a></li>
<li><a href="http://www.prescottlibrary.info/">Prescott Public Library</a></li>
<li><a href="http://www.pvaz.net/">Prescott Valley</a></li>
<li><a href="http://www.codepublishing.com/AZ/Prescott/?prescott01/Prescott0127.html">Procurement Code</a></li>
<li><a href="http://www.co.yavapai.az.us/">Yavapai County</a></li>
</ul>

<h3>State</h3>
<ul>
<li><a href="http://www.az.gov/">State of Arizona</a></li>
<li><a class="newlink" href="https://procure.az.gov/bso/">State Procurement Contracts</a></li>
<li><a href="http://www.dlapr.lib.az.us/">Library &amp; Public Records</a></li>
<li><a href="http://www.azleg.state.az.us/">State Legislature</a></li>
<li><a href="http://www.asrs.state.az.us/">State Retirement System</a></li>
<li><a href="http://www.azdot.gov/">Department of Transportation</a></li>
<li><a href="http://www.pr.state.az.us/">State Parks</a></li>
<li><a href="http://www.azland.gov/">Arizona Land Department</a></li>
<li><a href="http://www.azleg.state.az.us/ArizonaRevisedStatutes.asp">Arizona Statutes On-Line</a></li>
<li><a href="http://www.dps.state.az.us/welcome2.htm">Department of Public Safety</a></li>
<li><a href="http://www.psprs.com/">Public Safety Retirement System</a></li>
<li><a href="http://azdirect.state.az.us/">Government Telephone Directory</a></li>
</ul>

<h3>Other Government Resources</h3>
<ul>
<li><a href="http://www.icma.org/">ICMA</a></li>
<li><a class="newlink" href="http://www.icmarc.org/">ICMA Deferred Comp</a></li>
<li><a href="http://www.azleague.org/">League of AZ Cities &amp; Towns</a></li>
<li><a href="http://www.stateline.org/">State News</a></li>
<li><a href="http://www.officer.com/">Officer.com</a></li>
<li><a href="http://www.fs.fed.us/r3/prescott/">Prescott National Forest</a></li>
</ul>

<h3>Directories</h3>
<ul>
<li><a href="http://www.anywho.com/">Anywho.com</a></li>
<li><a href="http://zipfind.net/">Zipfind.net</a></li>
</ul>

<h3>Search the Web</h3>
<ul> 
<li><a href="http://www.google.com">Google</a></li>
<li><a href="http://www.altavista.com">AltaVista</a></li>
<li><a href="http://www.yahoo.com/">Yahoo</a></li>
<li><a href="http://www.ask.com">Ask Jeeves</a></li>
</ul>

</div><!-- /#column_1 -->
<div id="column_2">

<h3>Dictionaries and Resources</h3>
<ul>
<li><a href="http://www.dictionary.com/">Dictionary.com</a></li>
<li><a href="http://www.yourdictionary.com/">Your Dictionary</a></li>
<li><a href="http://www.thesaurus.com/">Thesaurus</a></li>
<li><a href="http://www.acronymfinder.com/">Acronym Finder</a></li>
</ul>

<h3>Local Sites</h3>
<ul>
<li><a href="http://www.prescott.org/">Prescott Chamber of Commerce</a></li>
<li><a href="http://www.pvchamber.org/">Prescott Valley Chamber</a></li>
<li><a href="http://www.chinovalley.org/">Chino Valley Chamber</a></li>
<li><a href="http://www.arizonahighways.com/">Arizona Highways</a></li>
</ul>

<h3>Newspapers</h3>
<ul>
<li><a href="http://www.prescottaz.com/">Daily Courier</a></li>
<li><a href="http://www.azcentral.com/">Arizona Republic</a></li>
<li><a href="http://www.gf.state.az.us/">Game and Fish</a></li>
<li><a href="http://www.prescottenews.com/">Prescott eNews</a></li>
</ul>


<h3>Education</h3>
<ul>
<li><a href="http://www.yc.edu">Yavapai College</a></li>
<li><a href="http://www.erau.edu/">Embry-Riddle</a></li>
<li><a href="http://www.prescott.edu/">Prescott College</a></li>
<li><a href="http://www.nau.edu/">NAU</a></li>
<li><a href="http://www.ncu.edu/">Northcentral University</a></li>
<li><a href="http://www.odu.edu/">Old Dominion</a></li>
</ul>

<h3>Weather</h3>
<ul>
<li><a href="http://wwwa.accuweather.com/index-forecast.asp?u=1&partner=accuweather&zipcode=86301">Accuweather Prescott</a> </li>
<li><a href="http://www.intellicast.com">Intellicast</a></li>
<li><a href="http://www.weather.com/">Weather Channel</a></li>
</ul>

<script type="text/javascript">
function confirm_alert(node) {
    return confirm("To access this information you will need a login and password.\nThe login is holmanfrazier\nThe password is YCT3950\nPlease write this down before clicking OK and continuing.");
}
</script>
<h3>Insurance</h3>
<ul>
<li><a href="http://www.azblue.com/provider-directory/health-dental.aspx">Blue Cross Blue Shield Provider Directory</a></li>
<li><a href="http://bcbsaz.spectralogix.com/searchform.php">Blue Cross Provider Search</a></li>
<li><a href="http://www.yctrust.net">Yavapai Combined Trust</a></li>
<li><a href="http://www.Holmangroup.com/holmanfrazier/" onclick="return confirm_alert(this);">Employee Assistance Program (EAP)</li>
<li><a href="http://www.summit-inc.net/">Summit Health</li>
<li><a href="https://www.mybenny.com/LoginPage.aspx">FSA ~ My Benny Card</li>
<li><a href="http://catamaranrx.com/">Catamaran Pharmacy Benefit Provider</li>
</ul>


</div><!-- /#column_2 -->

		</div>
		<?php endwhile; endif; ?>
	<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
	</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
