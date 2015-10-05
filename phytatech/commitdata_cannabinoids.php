<?php 

include "includes.php";

if (!logincheck()) {
   die("logout"); 
}

if (!empty($_POST["finalarr"])) {
   $finalarr = $_POST["finalarr"];
}
else
{
    die();
}

//$finalarr = '{"THC206656_2":{"guid":"2F2A9ED1-110F-4589-9CB8-7047027BB618","compoundname":"THC","sampleid":"206656-2","reporttype":"Infused II","sampletype":"Solid Edible","packageamount":13.7933,"injectiondate":"03-Sep-15, 07:45:28","ninjectiondate":1441287928,"percentage":"2.0444444444444447","samplemass":"1","area":"115.000","responsefactor":"1","dilution":"40","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"THCA206656_2":{"guid":"2F2A9ED1-110F-4589-9CB8-7047027BB618","compoundname":"THCA","sampleid":"206656-2","reporttype":"Infused II","sampletype":"Solid Edible","packageamount":13.7933,"injectiondate":"03-Sep-15, 07:45:28","ninjectiondate":1441287928,"percentage":"0.035555555555555556","samplemass":"1","area":"11.000","responsefactor":"5.5","dilution":"40","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBC206656_2":{"guid":"2F2A9ED1-110F-4589-9CB8-7047027BB618","compoundname":"CBC","sampleid":"206656-2","reporttype":"Infused II","sampletype":"Solid Edible","packageamount":13.7933,"injectiondate":"03-Sep-15, 07:45:28","ninjectiondate":1441287928,"percentage":"0.017777777777777778","samplemass":"1","area":"14.000","responsefactor":"14","dilution":"40","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBN206656_2":{"guid":"2F2A9ED1-110F-4589-9CB8-7047027BB618","compoundname":"CBN","sampleid":"206656-2","reporttype":"Infused II","sampletype":"Solid Edible","packageamount":13.7933,"injectiondate":"03-Sep-15, 07:45:28","ninjectiondate":1441287928,"percentage":"0.011154684095860566","samplemass":"1","area":"16.000","responsefactor":"25.5","dilution":"40","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"Tocopherol206656_2":{"guid":"2F2A9ED1-110F-4589-9CB8-7047027BB618","compoundname":"Tocopherol","sampleid":"206656-2","reporttype":"Infused II","sampletype":"Solid Edible","packageamount":13.7933,"injectiondate":"03-Sep-15, 07:45:28","ninjectiondate":1441287928,"percentage":"5.671111111111111","samplemass":"1","area":"319.000","responsefactor":"1","dilution":"40","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":"319.000"},"THC206656_1":{"guid":"B97FBBCF-608D-44BB-A079-4EDEF96CCF34","compoundname":"THC","sampleid":"206656-1","reporttype":"Infused II","sampletype":"Solid Edible","packageamount":13.7933,"injectiondate":"03-Sep-15, 07:18:27","ninjectiondate":1441286307,"percentage":"2.3466666666666667","samplemass":"1","area":"132.000","responsefactor":"1","dilution":"40","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"THCA206656_1":{"guid":"B97FBBCF-608D-44BB-A079-4EDEF96CCF34","compoundname":"THCA","sampleid":"206656-1","reporttype":"Infused II","sampletype":"Solid Edible","packageamount":13.7933,"injectiondate":"03-Sep-15, 07:18:27","ninjectiondate":1441286307,"percentage":"0.04525252525252525","samplemass":"1","area":"14.000","responsefactor":"5.5","dilution":"40","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBDA206656_1":{"guid":"B97FBBCF-608D-44BB-A079-4EDEF96CCF34","compoundname":"CBDA","sampleid":"206656-1","reporttype":"Infused II","sampletype":"Solid Edible","packageamount":13.7933,"injectiondate":"03-Sep-15, 07:18:27","ninjectiondate":1441286307,"percentage":"0.02318840579710145","samplemass":"1","area":"6.000","responsefactor":"4.6","dilution":"40","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBC206656_1":{"guid":"B97FBBCF-608D-44BB-A079-4EDEF96CCF34","compoundname":"CBC","sampleid":"206656-1","reporttype":"Infused II","sampletype":"Solid Edible","packageamount":13.7933,"injectiondate":"03-Sep-15, 07:18:27","ninjectiondate":1441286307,"percentage":"0.020317460317460317","samplemass":"1","area":"16.000","responsefactor":"14","dilution":"40","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBN206656_1":{"guid":"B97FBBCF-608D-44BB-A079-4EDEF96CCF34","compoundname":"CBN","sampleid":"206656-1","reporttype":"Infused II","sampletype":"Solid Edible","packageamount":13.7933,"injectiondate":"03-Sep-15, 07:18:27","ninjectiondate":1441286307,"percentage":"0.013246187363834424","samplemass":"1","area":"19.000","responsefactor":"25.5","dilution":"40","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"Tocopherol206656_1":{"guid":"B97FBBCF-608D-44BB-A079-4EDEF96CCF34","compoundname":"Tocopherol","sampleid":"206656-1","reporttype":"Infused II","sampletype":"Solid Edible","packageamount":13.7933,"injectiondate":"03-Sep-15, 07:18:27","ninjectiondate":1441286307,"percentage":"5.4222222222222225","samplemass":"1","area":"305.000","responsefactor":"1","dilution":"40","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":"305.000"},"THC206656_0":{"guid":"F1C8E661-4062-4DDF-A151-AC40557A7AF3","compoundname":"THC","sampleid":"206656-0","reporttype":"Infused II","sampletype":"Solid Edible","packageamount":13.7933,"injectiondate":"03-Sep-15, 06:51:28","ninjectiondate":1441284688,"percentage":"2.3644444444444446","samplemass":"1","area":"133.000","responsefactor":"1","dilution":"40","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBDA206656_0":{"guid":"F1C8E661-4062-4DDF-A151-AC40557A7AF3","compoundname":"CBDA","sampleid":"206656-0","reporttype":"Infused II","sampletype":"Solid Edible","packageamount":13.7933,"injectiondate":"03-Sep-15, 06:51:28","ninjectiondate":1441284688,"percentage":"0.02318840579710145","samplemass":"1","area":"6.000","responsefactor":"4.6","dilution":"40","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBC206656_0":{"guid":"F1C8E661-4062-4DDF-A151-AC40557A7AF3","compoundname":"CBC","sampleid":"206656-0","reporttype":"Infused II","sampletype":"Solid Edible","packageamount":13.7933,"injectiondate":"03-Sep-15, 06:51:28","ninjectiondate":1441284688,"percentage":"0.021587301587301586","samplemass":"1","area":"17.000","responsefactor":"14","dilution":"40","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBN206656_0":{"guid":"F1C8E661-4062-4DDF-A151-AC40557A7AF3","compoundname":"CBN","sampleid":"206656-0","reporttype":"Infused II","sampletype":"Solid Edible","packageamount":13.7933,"injectiondate":"03-Sep-15, 06:51:28","ninjectiondate":1441284688,"percentage":"0.016732026143790848","samplemass":"1","area":"24.000","responsefactor":"25.5","dilution":"40","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"Tocopherol206656_0":{"guid":"F1C8E661-4062-4DDF-A151-AC40557A7AF3","compoundname":"Tocopherol","sampleid":"206656-0","reporttype":"Infused II","sampletype":"Solid Edible","packageamount":13.7933,"injectiondate":"03-Sep-15, 06:51:28","ninjectiondate":1441284688,"percentage":"5.386666666666667","samplemass":"1","area":"303.000","responsefactor":"1","dilution":"40","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":"303.000"},"THC206655_2":{"guid":"865EF3F2-ACDC-4B98-8847-86EDE5E8C77D","compoundname":"THC","sampleid":"206655-2","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 05:57:28","ninjectiondate":1441281448,"percentage":"2.904","samplemass":"1","area":"2178.000","responsefactor":"1","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"THCA206655_2":{"guid":"865EF3F2-ACDC-4B98-8847-86EDE5E8C77D","compoundname":"THCA","sampleid":"206655-2","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 05:57:28","ninjectiondate":1441281448,"percentage":"0.06545454545454546","samplemass":"1","area":"270.000","responsefactor":"5.5","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBDA206655_2":{"guid":"865EF3F2-ACDC-4B98-8847-86EDE5E8C77D","compoundname":"CBDA","sampleid":"206655-2","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 05:57:28","ninjectiondate":1441281448,"percentage":"0.03710144927536232","samplemass":"1","area":"128.000","responsefactor":"4.6","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBD206655_2":{"guid":"865EF3F2-ACDC-4B98-8847-86EDE5E8C77D","compoundname":"CBD","sampleid":"206655-2","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 05:57:28","ninjectiondate":1441281448,"percentage":"0.26666666666666666","samplemass":"1","area":"142.000","responsefactor":"0.71","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBC206655_2":{"guid":"865EF3F2-ACDC-4B98-8847-86EDE5E8C77D","compoundname":"CBC","sampleid":"206655-2","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 05:57:28","ninjectiondate":1441281448,"percentage":"0.03876190476190476","samplemass":"1","area":"407.000","responsefactor":"14","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBGA206655_2":{"guid":"865EF3F2-ACDC-4B98-8847-86EDE5E8C77D","compoundname":"CBGA","sampleid":"206655-2","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 05:57:28","ninjectiondate":1441281448,"percentage":"0.02014814814814815","samplemass":"1","area":"68.000","responsefactor":"4.5","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBN206655_2":{"guid":"865EF3F2-ACDC-4B98-8847-86EDE5E8C77D","compoundname":"CBN","sampleid":"206655-2","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 05:57:28","ninjectiondate":1441281448,"percentage":"0.015320261437908496","samplemass":"1","area":"293.000","responsefactor":"25.5","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"Tocopherol206655_2":{"guid":"865EF3F2-ACDC-4B98-8847-86EDE5E8C77D","compoundname":"Tocopherol","sampleid":"206655-2","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 05:57:28","ninjectiondate":1441281448,"percentage":"0.772","samplemass":"1","area":"579.000","responsefactor":"1","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":"579.000"},"THC206655_1":{"guid":"8453FA62-09CD-4E58-BE2F-FDE61039E244","compoundname":"THC","sampleid":"206655-1","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 05:30:27","ninjectiondate":1441279827,"percentage":"3.1773333333333333","samplemass":"1","area":"2383.000","responsefactor":"1","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"THCA206655_1":{"guid":"8453FA62-09CD-4E58-BE2F-FDE61039E244","compoundname":"THCA","sampleid":"206655-1","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 05:30:27","ninjectiondate":1441279827,"percentage":"0.07878787878787878","samplemass":"1","area":"325.000","responsefactor":"5.5","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBDA206655_1":{"guid":"8453FA62-09CD-4E58-BE2F-FDE61039E244","compoundname":"CBDA","sampleid":"206655-1","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 05:30:27","ninjectiondate":1441279827,"percentage":"0.042028985507246375","samplemass":"1","area":"145.000","responsefactor":"4.6","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBD206655_1":{"guid":"8453FA62-09CD-4E58-BE2F-FDE61039E244","compoundname":"CBD","sampleid":"206655-1","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 05:30:27","ninjectiondate":1441279827,"percentage":"0.3098591549295775","samplemass":"1","area":"165.000","responsefactor":"0.71","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBC206655_1":{"guid":"8453FA62-09CD-4E58-BE2F-FDE61039E244","compoundname":"CBC","sampleid":"206655-1","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 05:30:27","ninjectiondate":1441279827,"percentage":"0.0440952380952381","samplemass":"1","area":"463.000","responsefactor":"14","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBGA206655_1":{"guid":"8453FA62-09CD-4E58-BE2F-FDE61039E244","compoundname":"CBGA","sampleid":"206655-1","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 05:30:27","ninjectiondate":1441279827,"percentage":"0.026962962962962963","samplemass":"1","area":"91.000","responsefactor":"4.5","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBN206655_1":{"guid":"8453FA62-09CD-4E58-BE2F-FDE61039E244","compoundname":"CBN","sampleid":"206655-1","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 05:30:27","ninjectiondate":1441279827,"percentage":"0.017045751633986927","samplemass":"1","area":"326.000","responsefactor":"25.5","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"Tocopherol206655_1":{"guid":"8453FA62-09CD-4E58-BE2F-FDE61039E244","compoundname":"Tocopherol","sampleid":"206655-1","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 05:30:27","ninjectiondate":1441279827,"percentage":"0.9333333333333333","samplemass":"1","area":"700.000","responsefactor":"1","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":"700.000"},"THC206655_0":{"guid":"2A70C78F-BFFA-4289-93EE-4C78764AC2B5","compoundname":"THC","sampleid":"206655-0","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 05:03:29","ninjectiondate":1441278209,"percentage":"3.176","samplemass":"1","area":"2382.000","responsefactor":"1","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"THCA206655_0":{"guid":"2A70C78F-BFFA-4289-93EE-4C78764AC2B5","compoundname":"THCA","sampleid":"206655-0","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 05:03:29","ninjectiondate":1441278209,"percentage":"0.07636363636363637","samplemass":"1","area":"315.000","responsefactor":"5.5","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBDA206655_0":{"guid":"2A70C78F-BFFA-4289-93EE-4C78764AC2B5","compoundname":"CBDA","sampleid":"206655-0","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 05:03:29","ninjectiondate":1441278209,"percentage":"0.042608695652173914","samplemass":"1","area":"147.000","responsefactor":"4.6","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBD206655_0":{"guid":"2A70C78F-BFFA-4289-93EE-4C78764AC2B5","compoundname":"CBD","sampleid":"206655-0","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 05:03:29","ninjectiondate":1441278209,"percentage":"0.2516431924882629","samplemass":"1","area":"134.000","responsefactor":"0.71","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBC206655_0":{"guid":"2A70C78F-BFFA-4289-93EE-4C78764AC2B5","compoundname":"CBC","sampleid":"206655-0","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 05:03:29","ninjectiondate":1441278209,"percentage":"0.045047619047619045","samplemass":"1","area":"473.000","responsefactor":"14","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBGA206655_0":{"guid":"2A70C78F-BFFA-4289-93EE-4C78764AC2B5","compoundname":"CBGA","sampleid":"206655-0","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 05:03:29","ninjectiondate":1441278209,"percentage":"0.026666666666666665","samplemass":"1","area":"90.000","responsefactor":"4.5","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBN206655_0":{"guid":"2A70C78F-BFFA-4289-93EE-4C78764AC2B5","compoundname":"CBN","sampleid":"206655-0","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 05:03:29","ninjectiondate":1441278209,"percentage":"0.01647058823529412","samplemass":"1","area":"315.000","responsefactor":"25.5","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"Tocopherol206655_0":{"guid":"2A70C78F-BFFA-4289-93EE-4C78764AC2B5","compoundname":"Tocopherol","sampleid":"206655-0","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 05:03:29","ninjectiondate":1441278209,"percentage":"0.9013333333333333","samplemass":"1","area":"676.000","responsefactor":"1","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":"676.000"},"THC206654_2":{"guid":"ABEF56F0-FB6E-4BA9-8BA1-A0C6EF92A629","compoundname":"THC","sampleid":"206654-2","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 04:09:28","ninjectiondate":1441274968,"percentage":"2.872","samplemass":"1","area":"2154.000","responsefactor":"1","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"THCA206654_2":{"guid":"ABEF56F0-FB6E-4BA9-8BA1-A0C6EF92A629","compoundname":"THCA","sampleid":"206654-2","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 04:09:28","ninjectiondate":1441274968,"percentage":"0.051393939393939395","samplemass":"1","area":"212.000","responsefactor":"5.5","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBDA206654_2":{"guid":"ABEF56F0-FB6E-4BA9-8BA1-A0C6EF92A629","compoundname":"CBDA","sampleid":"206654-2","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 04:09:28","ninjectiondate":1441274968,"percentage":"0.03913043478260869","samplemass":"1","area":"135.000","responsefactor":"4.6","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBD206654_2":{"guid":"ABEF56F0-FB6E-4BA9-8BA1-A0C6EF92A629","compoundname":"CBD","sampleid":"206654-2","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 04:09:28","ninjectiondate":1441274968,"percentage":"0.23286384976525823","samplemass":"1","area":"124.000","responsefactor":"0.71","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBC206654_2":{"guid":"ABEF56F0-FB6E-4BA9-8BA1-A0C6EF92A629","compoundname":"CBC","sampleid":"206654-2","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 04:09:28","ninjectiondate":1441274968,"percentage":"0.04419047619047619","samplemass":"1","area":"464.000","responsefactor":"14","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBCA206654_2":{"guid":"ABEF56F0-FB6E-4BA9-8BA1-A0C6EF92A629","compoundname":"CBCA","sampleid":"206654-2","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 04:09:28","ninjectiondate":1441274968,"percentage":"0.003914373088685015","samplemass":"1","area":"64.000","responsefactor":"21.8","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBGA206654_2":{"guid":"ABEF56F0-FB6E-4BA9-8BA1-A0C6EF92A629","compoundname":"CBGA","sampleid":"206654-2","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 04:09:28","ninjectiondate":1441274968,"percentage":"0.01985185185185185","samplemass":"1","area":"67.000","responsefactor":"4.5","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBN206654_2":{"guid":"ABEF56F0-FB6E-4BA9-8BA1-A0C6EF92A629","compoundname":"CBN","sampleid":"206654-2","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 04:09:28","ninjectiondate":1441274968,"percentage":"0.014745098039215686","samplemass":"1","area":"282.000","responsefactor":"25.5","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"Tocopherol206654_2":{"guid":"ABEF56F0-FB6E-4BA9-8BA1-A0C6EF92A629","compoundname":"Tocopherol","sampleid":"206654-2","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 04:09:28","ninjectiondate":1441274968,"percentage":"0.7626666666666667","samplemass":"1","area":"572.000","responsefactor":"1","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":"572.000"},"THC206654_1":{"guid":"861EC896-F27D-4C67-A01F-666F58AFE985","compoundname":"THC","sampleid":"206654-1","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 03:42:27","ninjectiondate":1441273347,"percentage":"2.9733333333333336","samplemass":"1","area":"2230.000","responsefactor":"1","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"THCA206654_1":{"guid":"861EC896-F27D-4C67-A01F-666F58AFE985","compoundname":"THCA","sampleid":"206654-1","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 03:42:27","ninjectiondate":1441273347,"percentage":"0.053575757575757575","samplemass":"1","area":"221.000","responsefactor":"5.5","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBDA206654_1":{"guid":"861EC896-F27D-4C67-A01F-666F58AFE985","compoundname":"CBDA","sampleid":"206654-1","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 03:42:27","ninjectiondate":1441273347,"percentage":"0.04115942028985507","samplemass":"1","area":"142.000","responsefactor":"4.6","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBD206654_1":{"guid":"861EC896-F27D-4C67-A01F-666F58AFE985","compoundname":"CBD","sampleid":"206654-1","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 03:42:27","ninjectiondate":1441273347,"percentage":"0.244131455399061","samplemass":"1","area":"130.000","responsefactor":"0.71","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBC206654_1":{"guid":"861EC896-F27D-4C67-A01F-666F58AFE985","compoundname":"CBC","sampleid":"206654-1","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 03:42:27","ninjectiondate":1441273347,"percentage":"0.04666666666666666","samplemass":"1","area":"490.000","responsefactor":"14","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBCA206654_1":{"guid":"861EC896-F27D-4C67-A01F-666F58AFE985","compoundname":"CBCA","sampleid":"206654-1","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 03:42:27","ninjectiondate":1441273347,"percentage":"0.003975535168195718","samplemass":"1","area":"65.000","responsefactor":"21.8","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBGA206654_1":{"guid":"861EC896-F27D-4C67-A01F-666F58AFE985","compoundname":"CBGA","sampleid":"206654-1","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 03:42:27","ninjectiondate":1441273347,"percentage":"0.02074074074074074","samplemass":"1","area":"70.000","responsefactor":"4.5","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBN206654_1":{"guid":"861EC896-F27D-4C67-A01F-666F58AFE985","compoundname":"CBN","sampleid":"206654-1","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 03:42:27","ninjectiondate":1441273347,"percentage":"0.015215686274509805","samplemass":"1","area":"291.000","responsefactor":"25.5","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"Tocopherol206654_1":{"guid":"861EC896-F27D-4C67-A01F-666F58AFE985","compoundname":"Tocopherol","sampleid":"206654-1","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 03:42:27","ninjectiondate":1441273347,"percentage":"0.7546666666666666","samplemass":"1","area":"566.000","responsefactor":"1","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":"566.000"},"THC206654_0":{"guid":"C2D7F614-F1E7-4C2F-8033-73E38B2ADF1B","compoundname":"THC","sampleid":"206654-0","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 03:15:28","ninjectiondate":1441271728,"percentage":"2.958666666666667","samplemass":"1","area":"2219.000","responsefactor":"1","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"THCA206654_0":{"guid":"C2D7F614-F1E7-4C2F-8033-73E38B2ADF1B","compoundname":"THCA","sampleid":"206654-0","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 03:15:28","ninjectiondate":1441271728,"percentage":"0.05333333333333333","samplemass":"1","area":"220.000","responsefactor":"5.5","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBDA206654_0":{"guid":"C2D7F614-F1E7-4C2F-8033-73E38B2ADF1B","compoundname":"CBDA","sampleid":"206654-0","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 03:15:28","ninjectiondate":1441271728,"percentage":"0.03855072463768116","samplemass":"1","area":"133.000","responsefactor":"4.6","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBD206654_0":{"guid":"C2D7F614-F1E7-4C2F-8033-73E38B2ADF1B","compoundname":"CBD","sampleid":"206654-0","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 03:15:28","ninjectiondate":1441271728,"percentage":"0.2272300469483568","samplemass":"1","area":"121.000","responsefactor":"0.71","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBC206654_0":{"guid":"C2D7F614-F1E7-4C2F-8033-73E38B2ADF1B","compoundname":"CBC","sampleid":"206654-0","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 03:15:28","ninjectiondate":1441271728,"percentage":"0.046476190476190476","samplemass":"1","area":"488.000","responsefactor":"14","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBCA206654_0":{"guid":"C2D7F614-F1E7-4C2F-8033-73E38B2ADF1B","compoundname":"CBCA","sampleid":"206654-0","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 03:15:28","ninjectiondate":1441271728,"percentage":"0.0029357798165137615","samplemass":"1","area":"48.000","responsefactor":"21.8","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBGA206654_0":{"guid":"C2D7F614-F1E7-4C2F-8033-73E38B2ADF1B","compoundname":"CBGA","sampleid":"206654-0","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 03:15:28","ninjectiondate":1441271728,"percentage":"0.01985185185185185","samplemass":"1","area":"67.000","responsefactor":"4.5","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBN206654_0":{"guid":"C2D7F614-F1E7-4C2F-8033-73E38B2ADF1B","compoundname":"CBN","sampleid":"206654-0","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 03:15:28","ninjectiondate":1441271728,"percentage":"0.01526797385620915","samplemass":"1","area":"292.000","responsefactor":"25.5","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"Tocopherol206654_0":{"guid":"C2D7F614-F1E7-4C2F-8033-73E38B2ADF1B","compoundname":"Tocopherol","sampleid":"206654-0","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":29.5,"injectiondate":"03-Sep-15, 03:15:28","ninjectiondate":1441271728,"percentage":"0.756","samplemass":"1","area":"567.000","responsefactor":"1","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":"567.000"},"THC206653_2":{"guid":"24812B74-D907-4A47-8F3D-5D6278D17A73","compoundname":"THC","sampleid":"206653-2","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":251.374,"injectiondate":"03-Sep-15, 02:48:27","ninjectiondate":1441270107,"percentage":"0.050666666666666665","samplemass":"1","area":"38.000","responsefactor":"1","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"THCA206653_2":{"guid":"24812B74-D907-4A47-8F3D-5D6278D17A73","compoundname":"THCA","sampleid":"206653-2","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":251.374,"injectiondate":"03-Sep-15, 02:48:27","ninjectiondate":1441270107,"percentage":"0.0016969696969696968","samplemass":"1","area":"7.000","responsefactor":"5.5","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBC206653_2":{"guid":"24812B74-D907-4A47-8F3D-5D6278D17A73","compoundname":"CBC","sampleid":"206653-2","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":251.374,"injectiondate":"03-Sep-15, 02:48:27","ninjectiondate":1441270107,"percentage":"0.0009523809523809524","samplemass":"1","area":"10.000","responsefactor":"14","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"Tocopherol206653_2":{"guid":"24812B74-D907-4A47-8F3D-5D6278D17A73","compoundname":"Tocopherol","sampleid":"206653-2","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":251.374,"injectiondate":"03-Sep-15, 02:48:27","ninjectiondate":1441270107,"percentage":"0.7066666666666667","samplemass":"1","area":"530.000","responsefactor":"1","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":"530.000"},"THC206653_1":{"guid":"D0600AFD-F86F-4D55-BFF0-BECFCD5B3B42","compoundname":"THC","sampleid":"206653-1","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":251.374,"injectiondate":"03-Sep-15, 02:21:27","ninjectiondate":1441268487,"percentage":"0.048","samplemass":"1","area":"36.000","responsefactor":"1","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBC206653_1":{"guid":"D0600AFD-F86F-4D55-BFF0-BECFCD5B3B42","compoundname":"CBC","sampleid":"206653-1","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":251.374,"injectiondate":"03-Sep-15, 02:21:27","ninjectiondate":1441268487,"percentage":"0.0008571428571428572","samplemass":"1","area":"9.000","responsefactor":"14","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"Tocopherol206653_1":{"guid":"D0600AFD-F86F-4D55-BFF0-BECFCD5B3B42","compoundname":"Tocopherol","sampleid":"206653-1","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":251.374,"injectiondate":"03-Sep-15, 02:21:27","ninjectiondate":1441268487,"percentage":"0.7293333333333334","samplemass":"1","area":"547.000","responsefactor":"1","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":"547.000"},"THC206653_0":{"guid":"1A686739-5826-4CB3-AAE2-83EA10DF2250","compoundname":"THC","sampleid":"206653-0","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":251.374,"injectiondate":"03-Sep-15, 01:54:26","ninjectiondate":1441266866,"percentage":"0.050666666666666665","samplemass":"1","area":"38.000","responsefactor":"1","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBC206653_0":{"guid":"1A686739-5826-4CB3-AAE2-83EA10DF2250","compoundname":"CBC","sampleid":"206653-0","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":251.374,"injectiondate":"03-Sep-15, 01:54:26","ninjectiondate":1441266866,"percentage":"0.0009523809523809524","samplemass":"1","area":"10.000","responsefactor":"14","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"Tocopherol206653_0":{"guid":"1A686739-5826-4CB3-AAE2-83EA10DF2250","compoundname":"Tocopherol","sampleid":"206653-0","reporttype":"Infused II","sampletype":"Liquid Edible","packageamount":251.374,"injectiondate":"03-Sep-15, 01:54:26","ninjectiondate":1441266866,"percentage":"0.716","samplemass":"1","area":"537.000","responsefactor":"1","dilution":"3","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":"537.000"},"THC206652":{"guid":"1B31508C-AAA5-49E3-84F4-57C441B5B84C","compoundname":"THC","sampleid":206652,"reporttype":"Standard","sampletype":"Solid Edible","packageamount":56,"injectiondate":"02-Sep-15, 21:51:27","ninjectiondate":1441252287,"percentage":"2.5705288863183595","samplemass":"0.5187","area":"75.000","responsefactor":"1","dilution":"40","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBDA206652":{"guid":"1B31508C-AAA5-49E3-84F4-57C441B5B84C","compoundname":"CBDA","sampleid":206652,"reporttype":"Standard","sampletype":"Solid Edible","packageamount":56,"injectiondate":"02-Sep-15, 21:51:27","ninjectiondate":1441252287,"percentage":"1.0878180214564652","samplemass":"0.5187","area":"146.000","responsefactor":"4.6","dilution":"40","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBC206652":{"guid":"1B31508C-AAA5-49E3-84F4-57C441B5B84C","compoundname":"CBC","sampleid":206652,"reporttype":"Standard","sampletype":"Solid Edible","packageamount":56,"injectiondate":"02-Sep-15, 21:51:27","ninjectiondate":1441252287,"percentage":"0.051410577726367195","samplemass":"0.5187","area":"21.000","responsefactor":"14","dilution":"40","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBCA206652":{"guid":"1B31508C-AAA5-49E3-84F4-57C441B5B84C","compoundname":"CBCA","sampleid":206652,"reporttype":"Standard","sampletype":"Solid Edible","packageamount":56,"injectiondate":"02-Sep-15, 21:51:27","ninjectiondate":1441252287,"percentage":"0.0911869574351467","samplemass":"0.5187","area":"58.000","responsefactor":"21.8","dilution":"40","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBGA206652":{"guid":"1B31508C-AAA5-49E3-84F4-57C441B5B84C","compoundname":"CBGA","sampleid":206652,"reporttype":"Standard","sampletype":"Solid Edible","packageamount":56,"injectiondate":"02-Sep-15, 21:51:27","ninjectiondate":1441252287,"percentage":"0.0685474369684896","samplemass":"0.5187","area":"9.000","responsefactor":"4.5","dilution":"40","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBN206652":{"guid":"1B31508C-AAA5-49E3-84F4-57C441B5B84C","compoundname":"CBN","sampleid":206652,"reporttype":"Standard","sampletype":"Solid Edible","packageamount":56,"injectiondate":"02-Sep-15, 21:51:27","ninjectiondate":1441252287,"percentage":"0.06585930218541157","samplemass":"0.5187","area":"49.000","responsefactor":"25.5","dilution":"40","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"THCVA206652":{"guid":"1B31508C-AAA5-49E3-84F4-57C441B5B84C","compoundname":"THCVA","sampleid":206652,"reporttype":"Standard","sampletype":"Solid Edible","packageamount":56,"injectiondate":"02-Sep-15, 21:51:27","ninjectiondate":1441252287,"percentage":"0.03916996398199405","samplemass":"0.5187","area":"8.000","responsefactor":"7","dilution":"40","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"Tocopherol206652":{"guid":"1B31508C-AAA5-49E3-84F4-57C441B5B84C","compoundname":"Tocopherol","sampleid":206652,"reporttype":"Standard","sampletype":"Solid Edible","packageamount":56,"injectiondate":"02-Sep-15, 21:51:27","ninjectiondate":1441252287,"percentage":"28.070175438596486","samplemass":"0.5187","area":"819.000","responsefactor":"1","dilution":"40","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":"819.000"},"THC206651":{"guid":"711796F4-0B03-42D7-91A3-D1758F453C53","compoundname":"THC","sampleid":206651,"reporttype":"Standard","sampletype":"Concentrate","packageamount":"","injectiondate":"02-Sep-15, 21:24:28","ninjectiondate":1441250668,"percentage":"15.132519520510284","samplemass":"0.3031","area":"258.000","responsefactor":"1","dilution":"400","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBDA206651":{"guid":"711796F4-0B03-42D7-91A3-D1758F453C53","compoundname":"CBDA","sampleid":206651,"reporttype":"Standard","sampletype":"Concentrate","packageamount":"","injectiondate":"02-Sep-15, 21:24:28","ninjectiondate":1441250668,"percentage":"1.300570434017567","samplemass":"0.3031","area":"102.000","responsefactor":"4.6","dilution":"400","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBGA206651":{"guid":"711796F4-0B03-42D7-91A3-D1758F453C53","compoundname":"CBGA","sampleid":206651,"reporttype":"Standard","sampletype":"Concentrate","packageamount":"","injectiondate":"02-Sep-15, 21:24:28","ninjectiondate":1441250668,"percentage":"1.3685741168420154","samplemass":"0.3031","area":"105.000","responsefactor":"4.5","dilution":"400","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBNA206651":{"guid":"711796F4-0B03-42D7-91A3-D1758F453C53","compoundname":"CBNA","sampleid":206651,"reporttype":"Standard","sampletype":"Concentrate","packageamount":"","injectiondate":"02-Sep-15, 21:24:28","ninjectiondate":1441250668,"percentage":"0.1407676234466073","samplemass":"0.3031","area":"48.000","responsefactor":"20","dilution":"400","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"Tocopherol206651":{"guid":"711796F4-0B03-42D7-91A3-D1758F453C53","compoundname":"Tocopherol","sampleid":206651,"reporttype":"Standard","sampletype":"Concentrate","packageamount":"","injectiondate":"02-Sep-15, 21:24:28","ninjectiondate":1441250668,"percentage":"47.97829832471866","samplemass":"0.3031","area":"818.000","responsefactor":"1","dilution":"400","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":"818.000"},"THC206650":{"guid":"4E593EF5-1DD0-4CED-9FB1-E0BA9C0CA96B","compoundname":"THC","sampleid":206650,"reporttype":"Standard","sampletype":"Concentrate","packageamount":"","injectiondate":"02-Sep-15, 20:57:29","ninjectiondate":1441249049,"percentage":"20.425531914893615","samplemass":"0.2585","area":"297.000","responsefactor":"1","dilution":"400","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBNA206650":{"guid":"4E593EF5-1DD0-4CED-9FB1-E0BA9C0CA96B","compoundname":"CBNA","sampleid":206650,"reporttype":"Standard","sampletype":"Concentrate","packageamount":"","injectiondate":"02-Sep-15, 20:57:29","ninjectiondate":1441249049,"percentage":"0.18568665377176014","samplemass":"0.2585","area":"54.000","responsefactor":"20","dilution":"400","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"CBN206650":{"guid":"4E593EF5-1DD0-4CED-9FB1-E0BA9C0CA96B","compoundname":"CBN","sampleid":206650,"reporttype":"Standard","sampletype":"Concentrate","packageamount":"","injectiondate":"02-Sep-15, 20:57:29","ninjectiondate":1441249049,"percentage":"0.6634555821038924","samplemass":"0.2585","area":"246.000","responsefactor":"25.5","dilution":"400","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":""},"Tocopherol206650":{"guid":"4E593EF5-1DD0-4CED-9FB1-E0BA9C0CA96B","compoundname":"Tocopherol","sampleid":206650,"reporttype":"Standard","sampletype":"Concentrate","packageamount":"","injectiondate":"02-Sep-15, 20:57:29","ninjectiondate":1441249049,"percentage":"56.600042983021694","samplemass":"0.2585","area":"823.000","responsefactor":"1","dilution":"400","pthc":"2250","watercontent":"","instrumentname":"HPLC 1100","testtype":"Cannabinoids","tocopherol_peak_area":"823.000"}}';


$finalarr = json_decode($finalarr, true);

$finalarrcompounds = array();
$reportsarr = array();
$arrsampleids = array();
$finalsubsamplemass = 0;

$insertsampleid = "";

list($usec, $sec) = explode(' ', microtime());
$usec = str_replace("0.", "", $usec);
$ndatetime = date($sec) . $usec;

$adatetime = $aDateTimeGlobal;  

foreach ($finalarr as $arr) {
    $guid = $arr["guid"];
    $instrumentname = $arr["instrumentname"];
    $sampleid = $arr["sampleid"];
    $subsamplemass = $arr["samplemass"];
    $compoundname = $arr["compoundname"];
    $sampletype = $arr["sampletype"];
    $testtype = "Cannabinoids";
    $injectiondate = $arr["injectiondate"];
    $ninjectiondate = $arr["ninjectiondate"];
    $percentage = $arr["percentage"];
    $tocopherol_peak_area = "";
    if (strlen($arr["tocopherol_peak_area"]) > 0) {
        $tocopherol_peak_area = $arr["tocopherol_peak_area"];
    }
    
    $moisture_content = $arr["watercontent"];    
    
    $dbtesttype = "cannabinoids";
    
    $arrtocopherol[$sampleid][$sampleid] = $tocopherol_peak_area;
    
   if (strpos($sampleid, "-")) { 
        $dbtesttype = "homogeneity";
        $arrsn = explode("-", $sampleid); 
        $mainsampleid = $arrsn[0];
        
        $arrsubsamplemass[$mainsampleid][$sampleid] = $subsamplemass;        
        $arrtocopherol[$mainsampleid][$sampleid] = $tocopherol_peak_area;
   }
  
    if ($sampleid != $insertsampleid) {
        $insertsampleid = $sampleid;
        
        $sql = "update tblcannabinoids set dup = 'true' where sampleid = '$sampleid'";
        $stmt = $dbconn->prepare($sql);
        $stmt->execute();
        
        $sql = "insert into tblcannabinoids (sampleid, adatetime, ndatetime, sampletype, instrumentname, subsamplemass, injectiondate, ninjectiondate) values ('$sampleid', '$aDateTimeGlobal', '$ndatetime', '$sampletype', '$instrumentname', '$subsamplemass', '$injectiondate', '$ninjectiondate')";
        $stmt = $dbconn->prepare($sql);
        $stmt->execute();
    }

    $sql = "update tblcannabinoids set $compoundname = '$percentage' where sampleid = '$sampleid' and ndatetime = '$ndatetime' and (dup is null or dup = '' or dup <> 'true')";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();  
    
    if ($sampletype == "Flower") {
        $sql = "update tblsamples set moisture_content = :moisture_content where sample_id = '$sampleid'";
        $stmt = $dbconn->prepare($sql);
        $stmt->execute(array(':moisture_content'=>$moisture_content));      
    }
    
}

if (!empty($arrsubsamplemass)) {
    foreach ($arrsubsamplemass as $key=>$val) {
        $homosamplemass  = 0;
        foreach ($val as $key1=>$val1) {        
            $homosamplemass += $val1;        
        }
        $arrsubsamplemass1[$key] = $homosamplemass;       
    }
}

$arrsubsamplemass = [];

if (isset($arrtocopherol)) {
    foreach ($arrtocopherol as $key=>$val) {
        $tocolist  = '';
        foreach ($val as $key1=>$val1) {        
            $tocolist = $tocolist . $val1 . ", ";        
        }
        $arrtocopherol1[$key] = rtrim($tocolist, ", ");       
    }
}

$arrtocopherol = [];

/*
?>
<pre>
<?php print_r($arrtocopherol1) ?>
</pre>
----------------
<?php
*/


$arr = array();
$subsamplemass  = "";
 
foreach ($finalarr as $arrkey=>$arr) {   
    
    $guid = $arr["guid"];
    $instrumentname = $arr["instrumentname"];
    $sampleid = $arr["sampleid"];
    $compoundname = $arr["compoundname"];
    $sampletype = $arr["sampletype"];
    $testtype = "Cannabinoids";
    $injectiondate = $arr["injectiondate"];
    $ninjectiondate = $arr["ninjectiondate"];
    $percentage = $arr["percentage"];
    $packageamount = $arr["packageamount"];
    $reporttype = $arr["reporttype"];
    $mainsampleid = $sampleid;
    $samplemass = $arr["samplemass"];
       
    
    if (strpos($sampleid, "-")) {
        
            $arrsn = explode("-", $sampleid); 
            $mainsampleid = $arrsn[0];
            $insertsampleid = "";
            $counter = 0;
            
            $finalarrcompounds[$mainsampleid]['subsamplemass'] = $arrsubsamplemass1[$mainsampleid];            
            $finalarrcompounds[$mainsampleid]['tocopherol_peak_area'] = $arrtocopherol1[$mainsampleid];
      
            
            foreach ($finalarr as $key=>$arr1) {
                
                $sid = $arr1["sampleid"];                            

/*
?>
<pre>
<?php print_r($arr1) ?>
</pre>
<?php  
*/             
                
                 if (substr($sid, 0, strlen($mainsampleid)) === $mainsampleid) {                    
                    $counter = $counter + 1;
                    $compoundname = $arr1["compoundname"];
                    $percentage = $arr1["percentage"];

                    $arrcalc[$mainsampleid][$sid][$compoundname] = $percentage;

                    unset($finalarr[$key]);                    
                }    
            }
          

            $arrcalc1 = array();
            
            foreach ($arrcalc as $key=>$val) {                
                          
                if (isset($arrcalc[$key])) {
                foreach ($val as $key1=>$val1) {                
                    foreach ($val1 as $key2=>$val2) {
                        
                        if (strlen($key2) > 0) {
                            $arrcalc1[$key2][$key][$key1] = $val2;
                        }
                    }            
                }
                unset($arrcalc[$key]); 
                }                
            }

            
            $total = 0;
            $counter = 0;
            foreach ($arrcalc1 as $key=>$val) {            
                $compoundname = $key;            
               
                foreach ($val as $key1=>$val1) {
                    $arrtemp = array();
                    $sampleid = $key1;
                   
                    foreach ($val1 as $key2=>$val2) {                    
                        array_push($arrtemp, $val2);                   
                    }

                
/*
?>
 <pre>
<?php echo $sampleid ?>
<?php print_r($arrtemp) ?>
</pre>
----------------
<?php
*/

                                  
                    $mean = array_sum($arrtemp) / count($arrtemp);
                             
                    if ($key == "THC") {
                        $standarddeviation = round(standard_deviation($arrtemp), 2);                
                        $rsd = round(($standarddeviation / $mean) * 100, 2);                    
                        $finalarrcompounds[$key1]["thc_standard_deviation"] = $standarddeviation;
                        $finalarrcompounds[$key1]["thc_relative_standard_deviation"] = $rsd;
                    }   
                    
                    $finalarrcompounds[$key1][$key] = $mean * $packageamount; 

                        
                    
                }
            }
           
        }
        else
        {
            $finalarrcompounds[$mainsampleid][$compoundname]=$percentage;
            $finalarrcompounds[$mainsampleid]['subsamplemass']=$samplemass;
            $finalarrcompounds[$mainsampleid]['tocopherol_peak_area'] = $arrtocopherol1[$mainsampleid];
        }  
        
        $finalarrcompounds[$mainsampleid]['injectiondate'] = $injectiondate;
        $finalarrcompounds[$mainsampleid]['ninjectiondate'] = $ninjectiondate;
        $finalarrcompounds[$mainsampleid]['instrumentname'] = $instrumentname;
        $finalarrcompounds[$mainsampleid]['sampletype'] = $sampletype;  
         
        
        $finalsubsamplemass = $finalarrcompounds[$mainsampleid]['subsamplemass'];

        $arrsampleids["$mainsampleid"]=array("sampleid"=>$mainsampleid, "testtype"=>$testtype, "injectiondate"=>$injectiondate, "reporttype"=>$reporttype, "subsamplemass"=>$finalsubsamplemass);
}

/*
?>
<pre>
<?php print_r($finalarrcompounds) ?>
</pre>
<?php  
*/    
  

foreach ($finalarrcompounds as $key=>$val) {  

    $sql = "update tblcannabinoids set dup = 'true' where sampleid = '$key'";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();
  
    $sql = "insert into tblcannabinoids (sampleid, adatetime, ndatetime) values ('$key', '$aDateTimeGlobal', '$ndatetime')";
    $stmt = $dbconn->prepare($sql);
    $stmt->execute();
  
    foreach ($val as $key1=>$val1) {
        $key1 = strtolower(preg_replace('/\s+/', '', $key1)); 
        
        if ($key1 == 'tocopherol_peak_area') {
            $sql = "update tblsamples set $key1 = '$val1' where sample_id = '$key'";        
            $stmt = $dbconn->prepare($sql);
            $stmt->execute();
        }
        
       

//echo $key1 . " - " . $val1 . "<br />";
        
        $sql = "update tblcannabinoids set $key1 = '$val1' where sampleid = '$key' and ndatetime = '$ndatetime'";        
        $stmt = $dbconn->prepare($sql);
        $stmt->execute();   
        
        //echo "$sql<br />";

        //echo $key . ": " . $key1 . " - " . $val1 . "<br />";
    }
}


foreach($arrsampleids as $key=>$val) {    
    $sampleid = $val["sampleid"];
    $testtype = $val["testtype"];
    $reporttype = $val["reporttype"];
    $injectiondate = $val["injectiondate"];
    $postedsubsamplemass = $val["subsamplemass"];
    
    //echo "linkreport($sampleid, $testtype, $instrumentname, $reporttype);<br />";
    
    $arr = explode(",", $injectiondate);
    $tvinjectiondate = $arr[0];
    $arr = explode("-", $tvinjectiondate);
    $day = $arr[0];
    if (strlen($day) < 2) { 
        $day = "0" . $day;
    }
    $month = $arr[1];
    $month = date("m", strtotime($month));
    $year = $arr[2];
    $tvinjectiondate = $month . "/" . $day . "/20" . $year;
   
    $arrupdate = array();
    
    $arrupdate["date_test_completion_workflow"] = $tvinjectiondate;
    $arrupdate["ndate_test_completion_workflow"] = strtotime(date($tvinjectiondate));
    $arrupdate["date_report_generation_workflow"] = $aDateGlobal;
    $arrupdate["ndate_report_generation_workflow"] = $nDateGlobal;
    $arrupdate["sub_sample_mass_cannabinoids"] = $postedsubsamplemass;
        
    foreach ($arrupdate as $key1=>$val1) {
        $sql = "update tblsamples set $key1 = :val1 where sample_id=:sample_id";
        $stmt = $dbconn->prepare($sql);
        $stmt->execute(array(':val1'=>$val1, ':sample_id'=>$sampleid));
    }
    
   
    linkreport($sampleid, $testtype, $instrumentname, $reporttype, $dbtesttype, $moisture_content);
    
 }
    

//------- END OF MAIN PROCESSING ------------- FUNCTIONS ARE BELOW --------------------- //
    

function linkreport($sampleid, $testtype, $instrumentname, $reporttype, $dbtesttype, $moisture_content) {
    
    global $homeurl;
    
    $url = $homeurl . "/makereport.php";
      
    $fields_string = "sampleid=$sampleid&testtype=$testtype&instrumentname=$instrumentname&reporttype=$reporttype&dbtesttype=$dbtesttype&moisturecontent=$moisture_content";
    
   $strCookie = 'PHPSESSID=' . $_COOKIE['PHPSESSID'] . '; path=/';
 
    session_write_close();
 
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
    curl_setopt($ch,CURLOPT_MAXREDIRS,50);
    if(substr($url,0,8)=='https://'){
        // The following ensures SSL always works. A little detail:
        // SSL does two things at once:
        //  1. it encrypts communication
        //  2. it ensures the target party is who it claims to be.
        // In short, if the following code is allowed, CURL won't check if the 
        // certificate is known and valid, however, it still encrypts communication.
        curl_setopt($ch,CURLOPT_HTTPAUTH,CURLAUTH_ANY);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
    }
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
    curl_setopt( $ch, CURLOPT_COOKIE, $strCookie ); 
	curl_setopt($ch,CURLOPT_POST, strlen($fields_string));
	curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);    
    $result = curl_exec($ch);
    curl_close($ch);    
}


function standard_deviation($sample){
	if(is_array($sample)){
		$mean = array_sum($sample) / count($sample);
		foreach($sample as $key => $num) $devs[$key] = pow($num - $mean, 2);
        $devscount = (count($devs) - 1);
        if ($devscount == 0) {
            $devscount = 1;
        }
		return sqrt(array_sum($devs) / ($devscount));
	}
}

?>