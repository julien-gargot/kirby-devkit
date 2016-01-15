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
  socialnetworks:
    label: Your Social Network
    type:  structure
    entry: >
      <span class="fa-stack fa-lg">
        <i class="fa fa-square fa-stack-2x"></i>
        <i class="fa fa-{{icon}} fa-stack-1x fa-inverse"></i>
      </span> {{link}}</i>
    fields:
      icon:
        label: Icon
        type:  text
        width: 1/2
        icon:  share-alt
      link:
        label: Link
        type:  text
        width: 1/2
        icon:  link
    help: Go on <a href="http://fontawesome.io/icons/" target="_blank">http://fontawesome.io/icons/</a> to find the icon’s name you want to use.
