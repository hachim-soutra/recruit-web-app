1. New Design Element
   Proposition:
   I suggest the following two options:

Create a banner with a short message and a button redirecting users to the Job Listings page without any filters.

Add a "Jobs" menu between "Home" and "Companies Hiring" which redirects to the Job Listings page without filters.

Estimation: 1 day
Cost: €30

✅ Approved.
Please ensure the banner is catchy and clearly visible at first glance. It should also be mobile-friendly.

The design will be mobile friendly and also the banner will be visible and have a clear text indicating that the button redirects to all jobs we have on Recruit.ie.

2. Client Redirection Option & Tracking
   Feature divided into 5 parts:

Part 1:
Add two fields in the job posting form:

Field 1: Choose job type (Career Website / Recruit.ie).

If "Career Website" is selected, a new field appears for inserting the application URL.

Part 2:
Display different "Apply" buttons depending on whether the job is hosted on Recruit.ie or an external career site.

Part 3:
If a candidate clicks "Apply via Career Website", the job opens in a new tab. Once the candidate returns to Recruit.ie, a pop-up will ask them to confirm whether they applied. If they click "Yes", a form appears requesting their name, email, and CV to confirm their application. An email is then sent to both the recruiter and the candidate.

Part 4:
If the candidate does not respond to the pop-up, we will prompt them again whenever they view that job.

Part 5:
If the candidate confirms application, the job will appear as "Applied" in their dashboard.

Query:
For Part 3, how do we ensure that candidates return to Recruit.ie after visiting the career website?
What happens if they don’t return?

We cannot ensure if the candidate will back to recruite.ie website or not, for that we will implement the part 4 to ask him every time consult the job to confirm the application on career website.

Suggestion:
Can we also trigger an email follow-up to the candidate after they click "Apply via Career Website"? This would ask if they completed the application and include a link for them to confirm and upload their CV. Since a Recruit.ie account is required to apply, we’ll already have their email.

Good Idea to send him an email if he click on apply for a career website. yes it's possible to be implemented.

✅ Approved:
Your proposed flow (Part 1–5) is excellent. I suggest we integrate the email follow-up step to ensure we collect confirmations from candidates who don’t immediately return to the site.

3. Email Alerts & Integration with MailerLite
   Parts:

Part 1:
Add an "Email" column in the Admin panel to show alert email addresses.

Part 2:
Import all existing alert email addresses into MailerLite.

Part 3:
Use MailerLite’s API to automatically send new alerts to the dashboard upon creation.

Query:
Can we also import the CVs of all registered candidates (~7,561 in the database) to MailerLite?

Yes we can create an automated script on the server to insert all emails you want on your mailerlite account.

Can all candidate profiles be made visible within the Admin panel for easier management?

I think it exist already a page display the list of candidates in admin panel (link : https://www.recruit.ie/admin/candidate/list)

4. Subscription Payment Allocation for Bank Transfers
   Feature Details:

Part 1:
Add a form in the Admin panel allowing manual subscription creation.

Part 2:
Create scripts to automatically notify companies (with manual subscriptions) when expiration is near.

Query:
Will the reminder be sent via automated email?

Yes it will be automated
Can this be sent to all active paid clients before expiry?

For card credit payment it's already handled by stripe, the tool we use for payments, but if you want we can do it for all companies.
How many email alerts would be sent, and how many days in advance?

We can send three email

One email 7days before expiration time
One email 3days before expiration time
One email on the last of company subscription
