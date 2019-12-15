<?php

// class representing a song lyric
class Lyric
{
    public function __construct($text, $song, $lyricsalbum, $year) {
      $this->text = $text;
      $this->song = $song;
      $this->album = $lyricsalbum;
      $this->year = $year;
    }
}

// EOF
