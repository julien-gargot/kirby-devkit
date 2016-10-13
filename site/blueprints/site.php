<?php if(!defined('KIRBY')) exit ?>

title: Site
pages: default
fields:
  siteSettings:
    label: Site Settings
    type:  headline
  title:
    label: Title
    type:  title
  author:
    label: Author
    type:  text
  description:
    label: Description
    type:  textarea
  keywords:
    label: Keywords
    type:  tags
  socialNetworkSettings:
    label: Social Network Settings
    type:  headline
  ogimage:
    label: Site Thumbnail
    type:  url
    help:  URL of the thumbnail that will represent your website on the social networks.
