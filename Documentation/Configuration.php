<?php

// @DefaultReturnRequestStructure
// set in config/coreintegration.php, look at EndpointValidator.php > setMainPortionOfEndpointData
// dev note: this effects lots of tests
// default can only be one of the following:
    // fullInfo
    // dataOnly
// parameter to effect request
    // dataOnly => return only the data requested
    // fullInfo => return all information for the resource
    // formData => return only the form data for the resource
    // columnData => return only the column data for the resource
