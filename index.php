<?php 

require __DIR__ . '/header.php';

?>

<?php 

$statement = $database->prepare('SELECT posts.*, user.email
FROM posts
INNER JOIN user
ON posts.user_id = user.id
ORDER BY posts.id DESC');
$statement->execute();

$posts = $statement->fetchAll(PDO::FETCH_ASSOC);

?>


<article>
    <h1 class="title"><?php echo $config['title']; ?></h1>
    <?php if (isset($_SESSION['user'])) : ?>
        <p>Welcome, <?= $_SESSION['user']['name'] ?>!</p>
    <?php endif; ?>
</article>

<article class="content-post">
        <button class="new-btn active">New</button>
        <button class="upvoted-btn"><a href="/upvoted.php">Most upvoted</a></button>
<ol>
        <?php foreach ($posts as $post) : ?>
        <?php if (isset($_SESSION['user'])) {
            $post_id = $post['id'];
            $user_id = $_SESSION['user']['id'];

            $statement = $database->prepare('SELECT * FROM upvotes WHERE post_id = :post_id AND user_id = :user_id');
            $statement->bindParam(':post_id', $post_id, PDO::PARAM_INT);
            $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $statement->execute();
            $upvote = $statement->fetch();
        }
        ?>

<li>

<?php if (isset($_SESSION['user'])) : ?>
<button data-link="<?= $post['id']; ?>" class="upvote-btn
<?php if (isset($_SESSION['user'])) : ?>
<?php if ($upvote !== false) : ?>
upvote-btn-darker
<?php endif; ?>
<?php endif; ?>">
        

        
</button>
<?php endif; ?>

    <a href="<?= $post['link']; ?>" class="list-item-title">
    <?= $post['title']; ?>
    </a>
    </li>

<div class="subtext">
<p>
<?= convertTime(strtotime($post['published'])); ?>

</p>
<p>
<?= $post['email']; ?>
</p>

<?php $upvotes = countUpvotes($database, $post['id']); ?>
<?php $numberOfComments = countComments($database, $post['id']); ?>

<div>
    <?php if ($upvotes == 1) : ?>
    <span class="number-of-votes" data-url="<?= $post['id']; ?>">
    <?= $upvotes; ?> vote
    </span>
    <?php else : ?>
    <span class="number-of-votes" data-url="<?= $post['id']; ?>">
    <?= $upvotes; ?> votes 
    </span>
    <?php endif; ?>

    
</div>




</div>




<?php endforeach; ?>
</ol>
</article>

<?php require __DIR__ . '/footer.php'; ?>