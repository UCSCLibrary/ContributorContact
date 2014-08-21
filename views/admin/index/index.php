<?php

$head = array('bodyclass' => 'contibutor-contact primary', 
              'title' => html_escape(__('Contributor Contact | Import documents')));
echo head($head);
echo flash(); 
?>
<a href="MailTo:<?php echo($emailString)?>"><button>Email All Contributors</button></a>
<button id="displayContribEmails">Display All Contributor Emails</button>
<?php
    if(count($users)>0) {
        echo('<h2>List of Contributors</h2><table><tr><td><strong>Name</strong></td><td><strong>Username</strong></td><td><strong>Email</strong></td></tr>');
        foreach($users as $user){
            echo('<tr><td>');
            echo($user['name']);
            echo('</td><td>');
            echo($user['username']);
            echo('</td><td>');
            echo(str_replace('@',' -at- ',$user['email'])) ;
            echo('</td></tr>' );
    }
        echo('</table>');
    }

echo foot(); 
?>