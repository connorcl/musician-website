<!-- Privacy policy page -->

<?php session_start(); ?>

<!doctype html>
<html lang="en">
  <head>
    <!-- meta elements -->
    <meta charset="utf-8">
    <meta name="description" content="Privacy policy page for a website dedicated to the singer-songwriter John Smith">
    <meta name="keywords" content="privacy,policy,music,musician,singer,songwriter,country,folk,americana">
    <meta name="author" content="Connor Claypool">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- page title -->
    <title>John Smith - Privacy Policy</title>
    <!-- Universal stylesheets -->
    <?php require_once "css-includes.php"; ?>
  </head>

  <body>
    <!-- Include cookies consent dialog -->
    <?php require_once "cookies-consent.php" ?>

    <!-- Navigation bar -->
    <?php require_once "navbar.php"; ?>

    <!-- Main content -->
    <main class="container">
      <div class="row mt-5">
        <div class="col-sm-2"></div>
        <div class="col-sm-8">
          <article aria-labelledby="privacy-header" class="text-light">
            <h1 id="privacy-header">Privacy Policy</h1>
            <hr class="midground">
            <h4>What personal information does this website collect?</h4>
            <p>
              When you create an account on this website, we store the information which you
              provide in the registration form. This information will include your email 
              address, your chosen username, your password and whether you have agreed to 
              receive marketing emails. Note that your password is stored in a hashed (encrypted) 
              format, and not as plain text. Additionally, we store any content you publish
              on the website forum.
            </p>
            <h4>Why is this information collected?</h4>
            <p>
              This information is collected so that the website can provide its core features.
              Your username and password are stored in order to verify the details you enter
              when you log in and allow you to access your account. Your username is also 
              required to identify you on the website's forum. The content you publish on 
              the forum must also be stored so that other users can view it. Your email address
              is used to verify your account and to allow you to reset a forgotten password,
              and to send marketing emails if you have agreed to this.
            </p>
            <h4>How is this information processed?</h4>
            <p>
              This information is stored in the website's database as soon as you provide it, and is 
              used only for the purposes described above. The information you provide is not subject 
              to any futher processing, profiling or analysis by this website or any third party.
            </p>
            <h4>How long is this information kept for?</h4>
            <p>
              The data you provide is kept until you delete it, or ask for it to be removed by
              contacting us using the email address given in the next section. If you delete your 
              account, all the information you provided in the registration form and any posts
              you published on the website forum are deleted. Threads you created are not deleted,
              to prevent disruption for other users, but they will no longer be associated with 
              your account.
            </p>
            <h4>Who can I contact with questions or concerns about this privacy policy?</h4>
            <p>
              If you have any questions, comments or concerns related to privacy or the use of
              personal information by this website, please send an email to gdpr@this-website.com.
            </p>
            <h4>Is my personal information stored securely?</h4>
            <p>
              Your data is stored in a secure database. If we do lose your data, we will be fined
              by the Information Commissioner.
            </p>

          </article>
        </div>
        <div class="col-sm-2"></div>
      </div>
    </main>

    <!-- Include footer -->
    <?php require_once "footer.php"; ?>

    <!-- Universal scripts -->
    <?php require_once "js-includes.php"; ?>
  </body>
</html>
