<?php

// include song lyric class file
require_once "class-lyric.php";


// array of Lyric objects
$lyrics[] = new Lyric(
  "Sample Lyric",
  "Song",
  "Album",
  2004);
$lyrics[] = new Lyric(
  "Sample Lyric",
  "Song",
  "Album",
  2004);
$lyrics[] = new Lyric(
  "Sample Lyric",
  "Song",
  "Album",
  2004);
$lyrics[] = new Lyric(
  "Sample Lyric",
  "Song",
  "Album",
  2004);
$lyrics[] = new Lyric(
  "Sample Lyric",
  "Song",
  "Album",
  2004);
$lyrics[] = new Lyric(
  "Sample Lyric",
  "Song",
  "Album",
  2004);
$lyrics[] = new Lyric(
  "Sample Lyric",
  "Song",
  "Album",
  2004);

// get random index
$index = rand(0, count($lyrics) - 1);

// encode randomly selected object as JSON
$lyric = json_encode($lyrics[$index]);
// output generated JSON
echo($lyric);

// EOF
