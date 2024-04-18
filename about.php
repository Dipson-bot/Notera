<!--<body onload="document.getElementById('content-wrapper').focus()"><div id="grid">
    
    <div id="header">
        
        <h1><a href="/">Notera</a></h1>
        
        <ul id="primary-nav">
            
            <li><a id="nav-about" href="/about">About E-Book</a></li>
            <li><a id="nav-demo" href="/demo">Demo</a></li>
            <li><a id="nav-download" href="/download">Download</a></li>
            <li><a id="nav-help" href="/help">Help</a></li>
            <li><a id="nav-whats-new" href="/whats-new">What's New</a></li>
            <li><a id="nav-get-involved" href="/get-involved">Get Involved</a></li>
           
            
            <li><a id="nav-lang" href="javascript:choose_language()">Language</a></li>
            
            
        </ul>
        <div id="donate" alt="Contribute to support calibre development" rel="#donate_box" data-location="top_banner" style="cursor: pointer; border: none; --darkreader-inline-border-top: initial; --darkreader-inline-border-right: initial; --darkreader-inline-border-bottom: initial; --darkreader-inline-border-left: initial;" data-darkreader-inline-border-top="" data-darkreader-inline-border-right="" data-darkreader-inline-border-bottom="" data-darkreader-inline-border-left="">Support Calibre</div>
    </div>
    
    <div id="content-wrapper" class="yui-cssbase" tabindex="-1">

    <?php
	session_start();
	function get_user_issue_book_count(){
		$connection = mysqli_connect("localhost","root","");
		$db = mysqli_select_db($connection,"lms");
		$user_issue_book_count = 0;
		$query = "select count(*) as user_issue_book_count from issued_Books where student_id = $_SESSION[id]";
		$query_run = mysqli_query($connection,$query);
		while ($row = mysqli_fetch_assoc($query_run)){
			$user_issue_book_count = $row['user_issue_book_count'];
		}
		return($user_issue_book_count);
	}
?>    <div id="content">-->
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bootstrap demo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>

<body>
<nav class="navbar navbar-expand-lg bg-dark border-bottom border-bottom-dark" data-bs-theme="dark">
      <div class="container-fluid">
        
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
		  
				<a class="navbar-brand" href="user_dashboard.php">Notera</a>
				<li class="nav-item">
              <a class="nav-link active" href="listofBooks.php">List of Books</a>
            </li>
            <li class="nav-item ">
              <a class="nav-link active" href="contact.php">Contact Us</a>
            </li>
            
			<li class="nav-item">
              <a class="nav-link active" href="aboutus.php">About Us</a>
            </li>
			<li class="nav-item dropdown">
	        	<a class="nav-link dropdown-toggle active" role="button" data-bs-toggle="dropdown">My Profile </a>
	        	<ul class="dropdown-menu">
	        		<a class="dropdown-item" href="view_profile.php">View Profile</a>
	        		<div class="dropdown-divider"></div>
	        		<a class="dropdown-item" href="edit_profile.php">Edit Profile</a>
	        		<div class="dropdown-divider"></div>
	        		<a class="dropdown-item" href="change_password.php">Change Password</a>
	        	</ul>
		      </li>
			  <li class="nav-item">
		        <a class="nav-link" role="button" href="logout.php">Logout</a>
		      </li>
            </ul>



            <!-- search bar -->
            <form class="d-flex" action="search.php" method="post">
                              <input class="form-control me-2" type="search" placeholder="Search by Book Name" aria-label="Search" name="book_name">
                              <button class="btn btn-outline-success" type="submit">Search</button>
                          </form>

        </div>
      </div>
    </nav>
  <h1>About E-Book</h1>
  <ul class="tabs">
    

  <div class="panes">

    <div class="pane" id="features" style="display: block;">

      <b>Notera is a powerful and easy to use e-book manager</b>.
      It’ll allow to do nearly everything and it
      takes things a step beyond normal e-book software. It’s also <b>completely free</b>
      and <i>open source</i> and great for both <b>casual users</b> and computer experts.

      <ul id="feature-list">
        <li>
          <span class="feature-title">Save time on managing your e-book collection</span>
          <div class="feature-description">

            An e-book, short for electronic book, is a digital version of a printed book that can be read on electronic
            devices such as e-readers, tablets, smartphones, or computers. Here are some key features of e-Books:<p>

            <p>1. Portability: E-Books allow you to carry an entire library with you in a single device. You can store
              hundreds or even thousands of Books on a small electronic device, making it convenient for traveling or
              reading on the go.

            <p>2. Accessibility: E-Books are easily accessible, as you can purchase and download them instantly from
              online stores. There is no need to visit a physical Bookstore or wait for shipping. Many classic and
              public domain Books are also available for free online.

            <p>3. Adjustable text and font size: E-Books offer customizable reading experiences. You can change the font
              size, style, and background color to suit your preferences, making it easier for people with visual
              impairments or reading difficulties to enjoy the content.

            <p>4. Search and navigation: E-Books come with search functionality, allowing you to quickly find specific
              words, phrases, or sections within thNotera. You can also navigate through the content using bookmarks,
              table of contents, or hyperlinks, making it easy to jump between chapters or reference different sections.

            <p>5. Interactive features: Some e-Books incorporate interactive elements such as hyperlinks, multimedia
              content (images, audio, videos), and interactive quizzes. These features enhance the reading experience by
              providing additional context, explanations, or interactive learning opportunities.

            <p>6. Annotation and highlighting: E-Books enable you to highlight text, make annotations, and add
              bookmarks. This feature is particularly useful for studying, research, or keeping track of important
              passages. Some e-book platforms also allow you to share annotations with others or access community
              discussions related to thNotera.

            <p>7. Synchronization across devices: Most e-book platforms offer synchronization capabilities, allowing you
              to start reading on one device and continue seamlessly on another. Your bookmarks, annotations, and
              reading progress are synced across devices, ensuring a consistent reading experience.

            <p>8. Eco-friendly and space-saving: E-Books contribute to environmental sustainability by reducing paper
              usage and the carbon footprint associated with traditional book publishing. They also save physical space,
              as you don't need shelves or storage for a large collection of Books.

            <p>Overall, e-Books offer convenience, flexibility, and a range of features that enhance the reading
              experience in the digital age.








          </div>
        </li>
        <li>
          <span class="feature-title">Use it everywhere and with anything</span>
          <div class="feature-description">

            <p>E-book supports almost <b>every single e-reader</b> and
              is compatible with more devices with every update. You can
              transfer your e-Books from one device to another in
              seconds, <b>wirelessly</b> or with a cable. And you don’t
              need any additional tools to do that. calibre will send the
              <b>best file format</b> for your device converting it if
              needed, automatically.
            </p>

            <p>No matter what PC, laptop or tablet you use, calibre’s
              got you covered. If you’re traveling and don’t have
              your device with you – you can take calibre on a USB stick
              and <b>use it wherever you are</b>. Or run the calibre Content server and read
              your Books anywhere, on any device.</p>

            <p>E-book can convert dozens of file types. No matter
              where you got your e-book from, it’ll be ready for your
              device in no time. When converting, you can also
              automatically change thNotera’s style, create a table of
              contents or improve punctuation and margins. calibre will
              also detect the format that’s best suited for your device
              on its own, so you don’t have to bother.</p>

            <p>E-book can also turn your personal documents to e-Books
              or create them from scratch. It can also take all the
              mundane things that go with it off your plate. It has
              automatic style helpers and scripts generating thNotera’s
              structure. You focus on the content, e-book will take care
              of the rest.</p>

          </div>
        </li>
        <li>
          <span class="feature-title" href="#">Comprehensive e-book viewer</span>
          <div class="feature-description">

            <p>E-book has a built-in e-book viewer that can
              display <b>all the major e-book formats</b>. It has full
              support for Table of Contents, highlighting, bookmarks,
              CSS, read aloud, a reference mode, printing, searching,
              copying, multi-page view, embedded fonts, and on and on…
            </p>

            <p>E-book's user interface is designed to be as simple as
              possible. <b>Large buttons</b> in the main window take care of
              most of your needs. The vast number of E-book’s features
              and options is always clearly displayed under <b>intuitive
                tabs</b>. Its context menus are neatly sorted, so the things
              you’re looking for almost find themselves on their own.
              <b>You’re never more than three clicks away from your goal.</b>
              It’s the result of years of tweaking E-book’s interface
              based on users’ feedback. Plus, you can change many aspects
              of how e-book looks and feels, and try one of the three
              built-in library views to browse your book collection using
              covers, titles, tags, authors, publishers, etc.
            </p>

            <p>E-book not only can <b>download all the metadata</b>
              for an e-book (things like title, author, publisher, ISBN,
              tags, cover or summary) but will also allow you to edit or
              create metadata in existing or new fields. That way you can
              track which Books you’ve read and which ones you liked. You
              can also take advantage of the <b>advanced search and
                sorting</b> functions that use tags, authors, comments and
              more. You will find thNotera you were looking for in seconds!
            </p>

          </div>
        </li>



        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
          crossorigin="anonymous"></script>
</body>

</html>