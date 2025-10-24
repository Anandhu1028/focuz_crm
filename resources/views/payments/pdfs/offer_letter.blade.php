<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Offer Letter - Focuz Academy</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            color: #333;
             background: white;
            
        }

        .page {
            width: 85%;
            background: white;
            margin: 0 auto 10mm auto;
            padding: 10mm 54px; /* equal left and right padding */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            page-break-after: always;
            position: relative;
            padding-top: 25px;
            padding-right: 54px;
        }
        

       .page::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            width: 400px;
            height: 400px;
            background: url("images/logo.jpg") no-repeat center center;
            background-size: contain;
            opacity: 0.2;  /* make more visible for testing */
            transform: translate(-50%, -50%);
            z-index: 0;
            pointer-events: none;
        }



        .page * {
            position: relative;
            z-index: 1;
        }

        /* Header */
       .header {
            display: flex;
            align-items: center;        /* vertically center logo and text */
            justify-content: center;     /* center everything horizontally */
            position: relative;
            height: 130px;               /* same as logo height for alignment */
        }
        
        /* Logo on the left, outside the center text */
        .logo {
            padding-top: 10px;
            position: absolute;          /* fix to left */
            left: 0;
            width: 138px;
            height: 120px;
            
        }
        
        /* Header text centered */
        .header-text {
            font-size: 41px;
            font-weight: bold;
            color: #4472C4;
            margin: 0;
            text-align: center;
        }


        /* Section Titles */
        .section-title {
            
            margin: 12px 0 20px 0;
            color: #4472C4;
            font-size: 19px;
            font-weight: bold;
        }

        /* Greetings */
        .dear-student {
            color: #4472C4;
            font-size: 14px;
            font-weight: bold;
        }

        .greeting p {
            margin-bottom: -2px;
            font-size: 14px;
        }

        .content-text {
            font-size: 14px;
            margin-bottom: 10px;
            text-align: justify;
            line-height: 1.6;
        }

        /* Student Table */
        .student-details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 8px 0;
            font-size: 13px;
            line-height: 1.5;
        }

        .student-details-table td {
            border: 1px solid #999;
            padding: 3px 12px;
            vertical-align: middle;
        }

        .student-details-table td:first-child {
            font-weight: normal;
            width: 40%;
        }

        .student-details-table a {
            color: #4472C4;
            text-decoration: none;
        }

        .footer {
            text-align: center;
            color: #000;
            font-weight: bold;
            font-size: 14px;
        }
        ul li::marker {
            color: #4472C4; 
            font-size:2em;/* Same as your heading color */
        }
               
        ul {
            margin-left: 40px;
            font-size: 12px;
            line-height: 1.8;
        }

        li {
            margin-bottom: 5px;
        }

        .highlight-box {
            background: #e6f2ff;
            border: 1px solid #4472C4;
            padding: 12px;
            margin: 12px 0;
            font-size: 11px;
        }

        .email-link {
            color: #4472C4;
            text-decoration: none;
        }
        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr; /* Two equal columns */
            gap: 40px; /* Space between columns */
            margin: 12px 0;
            width: 100%;
        }
        
        .two-column > div {
            /* Optional: add padding inside each column instead of margin-left */
            padding-left: 20px;
        }

        .column-box {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 11px;
        }

        .column-box h4 {
            color: #4472C4;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .executive-box {
            border: 2px solid #4472C4;
            padding: 15px;
            margin: 15px 0;
        }

        .executive-table {
            width: 450px;
            margin: 0 auto;
            font-size: 12px;
            border: 1px solid black;
            border-collapse: collapse;
        }

        .executive-table td {
            padding: 1px 12px;
            border: 1px solid black;
            vertical-align: middle;
        }

        .executive-table td:first-child {
            font-weight: bold;
        }

        .note-box {
            background: #fff9e6;
            border: 1px solid #ffcc00;
            padding: 12px;
            margin: 15px 0;
            font-size: 10px;
            line-height: 1.6;
        }

        .declaration-box {
            border: 2px solid #ffcc00;
            background: #fffef0;
            padding: 15px;
            margin: 15px 0;
            font-size: 11px;
        }

        .signature-section {
            margin-top: 20px;
        }

        .signature-field {
            flex: 1;
        }

        .signature-field label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .signature-line {
            border-bottom: 1px solid #333;
            margin-top: 25px;
        }

        .section-title-executiveDetails {
            font-size: 20px;
            color: #4472C4;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
        }

        .center-text {
            padding-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #2c3e50;
            line-height: 1.5;
        }

        .declaration-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            border: 2px solid #000;
            border-radius: 12px;
            overflow: hidden;
            margin: 15px 0;
        }

        .declaration-table th {
            color: #4472C4;
            text-align: center;
            padding: 10px;
            font-size: 18px;
            border-bottom: 2px solid #000;
        }

        .declaration-table td {
            padding: 15px;
            font-size: 16px;
            line-height: 1.6;
        }/* Watermark styling */
.watermark {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 400px;             /* size of watermark */
    height: 400px;
    opacity: 0.08;            /* faint */
    transform: translate(-50%, -50%);
    pointer-events: none;      /* so it doesn't block clicks or text selection */
    z-index: 0;               /* behind content */
}
.page * {
    position: relative;
    z-index: 1;               /* content above watermark */
}


        @media print {
            body {
                background: white;
                padding: 0;
            }
        }
        
    </style>
</head>

<body>
    <!-- PAGE 1 -->
    <div class="page" style="padding-top:30px">
        <img src="{{ asset('images/logo.png') }}" class="watermark">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="Focuz Academy Logo" class="logo">
            <span class="header-text" style="padding-left:50px">Focuz Academy</span>
        </div>


        <div class="section-title">Course Offer Letter</div>

        <div class="greeting">
            <div class="section-title">Dear Student,</div>
            <p>A warm greeting from Focuz Academy !!!</p>
            <p>We would like to inform you that you have duly registered for the educational programme.</p>
        </div>

        <div class="section-title">About Focuz</div>
        <p class="content-text" >The Focuz Academy is one of the pioneers in distance education and super child of a giant education entity, Brillainz Education Group, based in UAE. Focuz Academy is well-versed in providing state-of-the-art learning infrastructure and outstanding services at your convenience. We do facilitate a wide range of career choice in distance education across Kerala. We provide all sorts of educational services such as Distance Education, University Admission and Educational Consulting.<br><br>We are very pleased to inform you that you are duly registered in our course and that your registration details will be as follows:</p>

        <div class="section-title">Student Details</div>
         
        <table class="student-details-table">
            <tr>
                <td>Name of the Student</td>
                <td>{{ $student->first_name . ' ' . $student->last_name }}</td>
            </tr>
            <tr>
                <td>Registered Mobile Number</td>
                <td>+91 {{$student->phone_number}}</td>
            </tr>
            <tr>
                <td>Email Address</td>
                <td><a>{{$student->email}}</a></td>
            </tr>
            <tr>
                <td>Course / Specialization</td>
               <td>{{ $course_name ?? '-' }}</td>
            </tr>
            <tr>
                <td>University</td>
                <td>{{ $university_name ?? '-' }}</td>
            </tr>
            <tr>
                <td>Center</td>
                 <td>{{ $branch_name ?? '-' }}</td>
            </tr>
            <tr>
                <td>Track ID</td>
                 <td>{{ $track_id }}</td>
            </tr>
            <tr>
                <td>Total Fee</td>
               <td>{{ number_format($total_fee, 2) }}/-</td>
            </tr>
            <tr>
                <td>Paid</td>
                <td>{{ number_format($paid_amount, 2) }}/-</td>
            </tr>
            <tr>
                <td>Initial Receipt Number</td>
                 <td>{{ $initial_receipt }}</td>
            </tr>
            <tr>
                <td>Admission Executive</td>
                <td>{{$admission_executive ?? '-'}}</td>
            </tr>
            <tr>
                <td>Customer Relation Executive</td>
                <td>{{$customer_relation_executive ?? '-'}}</td>
            </tr>
            <tr>
                <td>Contact Number</td>
                <td>+91 8086652555</td>
            </tr>
            <tr>
                <td>Email</td>
                <td><a href="mailto:abhiachuzvz007@gmail.com">abhiachuzvz007@gmail.com</a></td>
            </tr>
        </table>

        <div class="footer" style="padding-top:20px">
            <p>www.focuzacademy.com</p>
        </div>
    </div>

    <!-- PAGE 2 -->
    <div class="page">
        <div class="header">
              <img src="{{ asset('images/logo.jpg') }}" alt="Focuz Academy Logo" class="logo">
            <div class="header-text">
                <h1></h1>
            </div>
        </div>

        <div class="section-title" style="padding-top: 60px;">Course Phases</div>
        <p class="content-text">Your course has different phases that make it easier to complete a degree certificate.<br>All of these phases are illustrated as follow.</p>

        <div class="phase-section">
            <div class="section-title" style="padding-top: 10px;">1. Admission Phase</div>

            <p class="content-text">This is the first step, once you have completed your admission discussion at an initial fee, you will be registered with focuz and will be assigned to a student relation executive.</p>
            <p class="content-text">Once the center registration has been completed, you will be provided with a track ID, through which we can track your application in the center. For the purpose of university registration, we will ask our student to send a clear copy of the documents listed below to the email ID, indicating the name and track ID as subject to:<br>
                <a href="mailto:focuz.admissionsc@gmail.com" class="email-link">focuz.admissionsc@gmail.com</a>
            </p>
            <ul class="stable-list" >
                <li>Secondary Certificate</li>
                <li>Higher secondary certificate or equivalent</li>
                <li>Graduation certificate</li>
                <li>Address proof like bank passbook or rent agreement</li>
                <li>Passport size photo</li>
            </ul>
            &nbsp;
            <div>
                <strong style="color: #4472C4;">Immediate Services after Admission</strong>
                <ul style="margin-top: 8px;">
                    <li>Student will receive receipt on same day of registration.</li>
                    <li>Student will receive course offer letter through mail on next day of admission.</li>
                    <li>Each Student will assigned to a student relation executive and CRE will be contacting the student on the next day of admission.</li>
                    <li>Student will receive an invitation for weekly lectures.</li>
                    <li>On the next day of admission, University assignment question with guidelines will be receiving in students registered mail id.</li>
                    <li>If any service not received on next day of admission, students can mail to: <br>focuz.solutions@gmail.com</li>
                </ul>
            </div>
        </div>

        <div class="footer" style="padding-top: 40px">
            <p>www.focuzacademy.com</p>
        </div>
    </div>

    <!-- PAGE 3 -->
    <div class="page">
        <div class="header">
             <img src="{{ asset('images/logo.jpg') }}" alt="Focuz Academy Logo" class="logo">
            <div class="header-text">
                <h1></h1>
            </div>
        </div>

        <div class="phase-section" style="padding-top: 60px;">
            <div class="section-title">2. Registration Phase</div>
            <ul>
                <li>Minimum fee of university registration will be first year fee.</li>
                <li>University registration will be completing with in four week of admission</li>
                <li>Students can check their University registration, Photo verification, Course & Specialization verification, Study material access, Pre-recorded classes, Exam notification, Exam result, etc...</li>
            </ul>

            <div class="two-column">
                <div style="margin-left:10px">
                    <h4>Total fee includes:</h4>
                    <ul>
                        <li>University Registration</li>
                        <li>Academic Registration</li>
                        <li>Examination Fee</li>
                        <li>Specialization Fee</li>
                        <li>Documentation Fee</li>
                        <li>Service Charge</li>
                    </ul>
                </div>
                <div style="margin-left:10px">
                    <h4>Total fee not includes:</h4>
                    <ul>
                        <li>Final Certificate Fee</li>
                        <li>Back Paper Fee<br><i>(If student fail/absent for any subject)</i></li>
                    </ul>
                </div>
            </div>


            <div class="section-title">3. Study Materials</div>
            <ul style="margin-left: 39px;">
                <li>University text books will only issued to the students after the center registration. There will be an extra charge for text book (Hard copy). Once you get the text books student should give the students affairs executive the acknowledgement</li>
                <li>Once completing LMS, student can access text book soft copy in their university portal.</li>
            </ul>

            <div class="section-title">4. University Registration Phase</div>
            <ul style="margin-left: 39px;">
                <li>University Registration begins as per the notification from the university and end on or before a specified date, which will be updated by your student's affairs executive.</li>
                <li>First semester fee has to be paid one month prior to university registration. If you do not meet the criteria, your application will not be registered and proceed to the next batch</li>
                <li>Student has to make sure we recieved all your educational documents via email, as mentioned in the initial admission stage.</li>
            </ul>
        </div>

        <div class="footer" style="padding-top: 47px;">
            <p>www.focuzacademy.com</p>
        </div>
    </div>

    <!-- PAGE 4 -->
    <div class="page">
        <div class="header">
             <img src="{{ asset('images/logo.jpg') }}" alt="Focuz Academy Logo" class="logo">
            <div class="header-text">
                <h1></h1>
            </div>
        </div>

        <div class="phase-section" style="padding-top: 60px;">
            <div class="section-title">5. Assignment</div>
            <ul style="margin-left: 39px;">
                <li>students must receive an acknowledgement from executives that their assignment or projects have been collected and accepted in accordance with the guidlines.</li>
                <li>Assignment questions and guidlines are available in student's portal.</li>
                <li>The Assignment should be hand written, and all subject of the semester the semester should be bound together and not separate</li>
                <li>The submission of assignment is mandatory one month prior to examination date. if not, there will be semester back paper fee</li>
                <li>After the submission only one examination hall ticket will be issued</li>
            </ul>
        </div>

        <div class="phase-section">
            <div class="section-title">6. Project Work</div>
            <ul style="margin-left: 39px;">
                <li>Project work is compulsory for student of final year.</li>
                <li>Students must strictly follow the guidlines to prepare a project</li>
                <li>The project submission is mandatory one month prior to examination date. If not, there will be a chance of failure.</li>
            </ul>
        </div>

        <div class="phase-section">
            <div class="section-title">7. Examination Phase</div>
            <ul style="margin-left: 39px;">
                <li>A qualifying fee have to be paid one month prior to the registration date of the examination at the university</li>
                <li>Academy will be registering student after checking their fee eligibility and assignment status.</li>
                <li>Students will recieve examination time table, Examination writing guidlines, and hall tickets before the examination itself.</li>
                <li>All students should strictly adhere to exam rules and regulations.</li>
            </ul>
        </div>

        <div class="footer" style="padding-top: 194px;">
            <p>www.focuzacademy.com</p>
        </div>
    </div>

    <!-- PAGE 5 -->
    <div class="page">
        <div class="header">
             <img src="{{ asset('images/logo.jpg') }}" alt="Focuz Academy Logo" class="logo">
            <div class="header-text">
                <h1></h1>
            </div>
        </div>

        <div class="phase-section" style="padding-top:50px">
            <div class="section-title">8. Result & Mark List Phase</div>
            <ul style="margin-left: 39px;">
                <li>After the examination, the university will publish the result within three months, our center will not be responsible for any delay on the part of the university.</li>
                <li>If you do not attend a minimum of 3 subject per semester, your academic year will be extended to the following year and you will have to pay the back-paper registration fee to reappear the examination</li>
            </ul>

            <div class="section-title">9. Convocation & Degree Certificate</div>
            <ul style="margin-left: 39px;">
                <li>The date and venue of the convocation ceremony shall be fixed by the university</li>
                <li>If you do not attend a minimum of 3 subject per semester, your academic year will be extended to the following year and you will have to pay the back-paper registration fee to reappear the examination There would be a fee for convocation and final certificate</li>
                <li>If, for any reason the student does not attend the convocation ceremony, they shall be asked to pay the Provisional certificate fee and to collect the certificate directly from the academy</li>
                <li>Our service extends until the end of the semester. Students have the option to apply for the main degree certificate directly at the university, or we can apply on their behalf, with charges applicable for the service.</li>
            </ul>

            <div class="section-title">10. Reference Claim</div>
            <p class="content-text">Students can refer their friend or relatives to the academy after after their successful enrollment they can claim 1500/- amount as discount voucher in their total fee and if the referee is not a student then can claim 100% of amount as cash.</p>

            <div class="section-title">Points to Remember</div>
            <ul class="points-list" style="margin-left: 39px;">
                <li>Students should keep all receipts issued from academy voucher.</li>
                <li>Students should complete their semester fee one month prior the examination.</li>
                <li>Fee needs to be remitted through official mediums, its compulsory to collect receipts against every remittance.</li>
                <li>Focuz would not be responsible for any type of staff commitments like assignments project etc...,</li>
                <li>If Students is not submitting assignment project on time, academy won't be responsible for further consequence.</li>
                <li>It is the responsibility of the student to submit genuine, approved Fee - certification on time for the university registration.</li>
            </ul>
        </div>

        <div class="footer" style="padding-top:20px">
            <p>www.focuzacademy.com</p>
        </div>
    </div>

    <!-- PAGE 6 -->
    <div class="page">
        <div class="header">
              <img src="{{ asset('images/logo.jpg') }}" alt="Focuz Academy Logo" class="logo">
            <div class="header-text">
                <h1></h1>
            </div>
        </div>

        <div class="section-title-executiveDetails" style="padding-top: 60px;">Student's Affairs Executive Details</div>
        <div class="">
            <table class="executive-table">
                <tr>
                    <td>NAME</td>
                    <td>ATHIRA ANILKUMAR</td>
                </tr>
                <tr>
                    <td>CONTACT NO.</td>
                    <td>+91 695626525</td>
                </tr>
                <tr>
                    <td>EMAIL ID</td>
                    <td>athiraanilkumar@gmail.com</td>
                </tr>
                <tr>
                    <td>BRANCH</td>
                    <td>Focuz Academy, Kochi</td>
                </tr>
            </table>
        </div>

        <div class="center-text">
            <p><i>If you feel free to convey your suggestions or complaints, Don't hesitste to contact us.<br>
                for any further queries regarding details of the course, suggestion and complaints,<br>
                feel free to contact our whats app assistance number @</i></p>
        </div>

        <div style="">
            <table class="declaration-table">
                <tr>
                    <th class="center-text">Declaration</th>
                </tr>
                <tr>
                    <td>
                        <p>I hereby declare that I accept and agree all the terms and conditions in the offer letter and will abide all the rules and regulations of the institute correctly. I understand that, if any kind of delay from any side which affects successful completion of my course the center would not be responsible.</p>

                        <div class="signature-section">
                            <p><strong>Name of student:</strong> _______________________________________________________</p>
                            <p><strong>Sign:</strong> _____________________________________ &nbsp;&nbsp;&nbsp;&nbsp; <strong>Date:</strong> ____________________</p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="center-text">
            <p><i>(Note: Dear Students, kindly make sure to either acknowledge the mail or singing and send back the signed softcopy to the same mail. If we are unable to recieve it will consider as verified and acknowledge it from your end.<br><br>
                untill and unless there is rejection from the university side, there won't be no refund approved.)</i></p>
        </div>

        <div class="footer" style="padding-top:160px;">
            <p>www.focuzacademy.com</p>
        </div>  
    </div>
    

</body>
</html>