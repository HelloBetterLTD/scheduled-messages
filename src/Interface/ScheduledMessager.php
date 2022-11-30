<?php

namespace SilverStripers\ScheduledMessages\Interface;

interface ScheduledMessager
{

    public function getMergeFields() : array;

    public function getComparisonFields() : array;

    public function getMessagePhoneNumber() : ?string;

    public function getMessageEmail() : ?string;

}
