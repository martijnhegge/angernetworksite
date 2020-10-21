<div class="cms">
<?php idle_logout();?>
<?php include "includes/header.php" ?>
<?php include 'includes/navbar.php' ?>
	<div id="indexHeader">
		<div class="title cmspage"><h2>Dashboard - CMS van MH-Developent</h2>Welkom <?php echo htmlspecialchars( $_SESSION['username']) ?>. <a href="index.php?action=logout"?>Log out</a></div>
	</div>
<div class="container">
	<div class="row">
    	<div class="test one column">One</div>
    	<div class="test eleven columns">Eleven</div>
  	</div>

      <!-- <h1>All Articles</h1>

<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>


<?php if ( isset( $results['statusMessage'] ) ) { ?>
        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
<?php } ?>

      <table>
        <tr>
          <th>Publication Date</th>
          <th>Article</th>
        </tr>

<?php foreach ( $results['articles'] as $article ) { ?>

        <tr onclick="location='index.php?action=editArticle&amp;articleId=<?php echo $article->id?>'">
          <td><?php echo date('j M Y', $article->publicationDate)?></td>
          <td>
            <?php echo $article->title?>
          </td>
        </tr>

<?php } ?>

      </table>

      <p><?php echo $results['totalRows']?> article<?php echo ( $results['totalRows'] != 1 ) ? 's' : '' ?> in total.</p>

      <p><a href="index.php?action=newArticle">Add a New Article</a></p>
</div> -->

