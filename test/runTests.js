/***************************************************************************************************************************/
/**
    BASALT Extension Layer
    
    © Copyright 2018, Little Green Viper Software Development LLC/The Great Rift Valley Software Company
    
    LICENSE:
    
    MIT License
    
    Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation
    files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy,
    modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the
    Software is furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
    OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
    IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
    CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.


    Little Green Viper Software Development: https://littlegreenviper.com
*/

var pageHTML = '';
var testsToRun = Array();
var ajaxLoader = new ajaxLoader();

function runTests(inTestNameArray) {
    for (var i = 0; i < inTestNameArray.length; i++) {
        var testName = inTestNameArray[i];
        if (testName) {
            testsToRun.push(testName);
        };
    };
    
    nextTest();
};

function nextTest() {
    var progress_report = document.getElementById('progress-report');
            
    if(testsToRun.length) {
        var testName = testsToRun.shift();
        
        if (progress_report) {
            progress_report.innerHTML = 'Running ' + testName.toString() + '.';
        };
        
        ajaxLoader.ajaxRequest('test_scripts/' + testName.toString() + '.php', runTestCallback, 'GET');
    } else {
        if (progress_report) {
            progress_report.innerHTML = '';
        };
        showTests();
    };
};

function showTests() {
    var tests_displayed = document.getElementById('tests-displayed');
    
    if (tests_displayed) {
        tests_displayed.innerHTML = pageHTML;
        
        var tests_wrapped_up = document.getElementById('tests-wrapped-up');
        if (tests_wrapped_up) {
            var throbber_container = document.getElementById('throbber-container');
            
            if (throbber_container) {
                throbber_container.style.display = 'none';
            };
            
            tests_wrapped_up.style.display = 'table';
        };
    };
    
};

function runTestCallback (in_response_object) {
    if (in_response_object.responseText) {
        pageHTML += in_response_object.responseText;
    };
    nextTest();
};
