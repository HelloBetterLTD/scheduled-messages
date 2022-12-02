# Silverstripe Scheduled Messages

Send scheduled emails / text messages easy and fast. 


## Maintainers
nivanka@silverstripers.com

## Installation

Use composer to install on your SilverStripe 4 website.

```
composer require silverstripers/scheduled-messages dev-master
```

## Requirements

1. SilverStripe 4+
2. PHP 8.1 + 

## Basic usage

Require the package via composer and run dev build. http://mysite.com/dev/build?flush=all

The module will add schedule messages to the settings / site config area in the CMS. 

**Define the types you want to send messages against**

Pick your dataobjects that you want to send emails to and implement them by 

`SilverStripers\ScheduledMessages\Interface\ScheduledMessager`

That will essentially force you to implement four new methods. 

```
  // return the merge fields / methods available for the messages to use as merge tags 
  public function getMergeFields() : array;
  
  // return the fields that are used to run various comparison queries 
  public function getComparisonFields() : array;
  
  // returns the phone number to send text messages 
  public function getMessagePhoneNumber() : ?string;

  // return the email address to send emails to 
  public function getMessageEmail() : ?string;

```

## For text messages 

Unlike emails text messages are different. There are so many way to connect SMS gateways, email to SMS services, Rest apis, WSDLs, SOAP etc. The module lets you define your own SMS transporter. 

Create a class and implement `SilverStripers\ScheduledMessages\Interface\TextMessageTransporter`, which will force you to implement a `sendText` message. 


