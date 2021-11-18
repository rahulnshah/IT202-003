# Project Name: Simple Shop
## Project Summary: This project will create a simple e-commerce site for users. Administrators or store owners will be able to manage inventory and users will be able to manage their cart and place orders.
## Github Link: https://github.com/rahulnshah/IT202-003/tree/prod
## Project Board Link: https://github.com/rahulnshah/IT202-003/projects/1
## Website Link: http://rns22-prod.herokuapp.com/Project/
## Your Name: Rahul Shah

<!--
### Line item / Feature template (use this for each bullet point)
#### Don't delete this

- [ ] \(mm/dd/yyyy of completion) Feature Title (from the proposal bullet point, if it's a sub-point indent it properly)
  -  List of Evidence of Feature Completion
    - Status: Pending (Completed, Partially working, Incomplete, Pending)
    - Direct Link: (Direct link to the file or files in heroku prod for quick testing (even if it's a protected page))
    - Pull Requests
      - PR link #1 (repeat as necessary)
    - Screenshots
      - Screenshot #1 (paste the image so it uploads to github) (repeat as necessary)
        - Screenshot #1 description explaining what you're trying to show
### End Line item / Feature Template
--> 
### Proposal Checklist and Evidence

- Milestone 1
  - [x] \(11/09/2021) User will be able to register a new account
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/register.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/20
        - https://github.com/rahulnshah/IT202-003/pull/30
        - https://github.com/rahulnshah/IT202-003/pull/10
      - Screenshots
          ![image](https://user-images.githubusercontent.com/68120349/141045585-15ac2d20-bfcf-419c-b98a-3a3be16629f7.png)
          - This is the user registration page.  The user must fill in all required fields and register with a unique username and email address.

  - [x] \(11/09/2021) User will be able to login to their account (given they enter the correct credentials)
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/login.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/27
        - https://github.com/rahulnshah/IT202-003/pull/10
        - https://github.com/rahulnshah/IT202-003/pull/30
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/141045768-bef46e85-7f18-401e-9f75-3b9aff5407c4.png)
          - This is the user login page.  The user must fill in all required fields and login with either a username or email address that he or she is registered with and a valid password.
        ![image](https://user-images.githubusercontent.com/68120349/141046091-91891757-54b5-42c0-b14a-8d528c21e024.png)
          - This is the landing page of a logged-in user.

  - [x] \(11/09/2021) User will be able to logout
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/logout.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/30
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/141046190-f3f8c0d5-ba3d-4c64-8ab5-d47a6e686e7f.png)
          - The logout page redirects the user to the login page.  Old session keys are erased and a new session is started. 

  - [x] \(11/09/2021) Basic security rules implemented
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: http://rns22-prod.herokuapp.com/Project/admin/
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/25
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/141156227-036cda54-16c9-4c6b-99cd-e966d987d85f.png)
          - Function called on appropriate pages that only allow logged in users. 
        ![image](https://user-images.githubusercontent.com/68120349/141156530-8ba9e156-241f-4338-8db8-69daab385fd2.png)
        ![image](https://user-images.githubusercontent.com/68120349/141157096-0a2e87cf-1835-419b-9d59-8b51109cb3ec.png)
        ![image](https://user-images.githubusercontent.com/68120349/141157261-c5137907-f686-4bb4-bad9-9acab8a4a125.png)
          - Functions called on appropriate pages that only allow role-specific actions, such as viewing the roles table, creating a role, and assigning a role. 

  - [x] \(11/09/2021) Basic Roles implemented
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: http://rns22-prod.herokuapp.com/Project/admin/
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/26
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/141155452-638fff8c-344c-42d2-93a3-29566e5fc28e.png)
          - Roles table 
        ![image](https://user-images.githubusercontent.com/68120349/141155734-62888988-0758-4c0a-9509-d97c123d9857.png)
          - User Roles table 

  - [x] \(11/09/2021) Site should have basic styles/theme applied; everything should be styled
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/home.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/30
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/141046319-de360654-6326-4727-b9f2-49ffe345346d.png)
        ![image](https://user-images.githubusercontent.com/68120349/141046345-332eafb5-d57c-4600-9b31-a72bf7e6338e.png)
        ![image](https://user-images.githubusercontent.com/68120349/141046398-1eba8868-e6eb-4f70-8cdb-30050a49d4b8.png)
          - Styled the navigation bar, all tables that a user has access to, and all forms in Bootstrap v5.1.

  - [x] \(11/09/2021) Any output messages/errors should be “user friendly”
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/login.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/21
        - https://github.com/rahulnshah/IT202-003/pull/30
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/141046525-daaa153a-7041-4df7-8fdd-5ff47450f4ca.png)
        ![image](https://user-images.githubusercontent.com/68120349/141046583-d44f0e8e-538d-4ced-a24c-da4ec1d0a8e1.png)
        ![image](https://user-images.githubusercontent.com/68120349/141046713-db1fdf7e-94f0-470d-92c9-06bb8532e077.png)
        ![image](https://user-images.githubusercontent.com/68120349/141046768-23ff1696-c4ab-420a-8891-93ab3a984dd7.png)
        ![image](https://user-images.githubusercontent.com/68120349/141159875-5dec4ce8-cf22-49cb-9e54-88bfb59e3d90.png)
          - User is shown user frindly messages whenever a query is aborted by the DBMS.  Some examples are shown above.

  - [x] \(11/09/2021) User will be able to see their profile
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/Profile.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/30
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/141047223-3e1d7418-7632-49e0-9567-48ff7e7285f9.png)
        ![image](https://user-images.githubusercontent.com/68120349/141047254-9b07083c-90f1-4c9a-8f09-b362648ce765.png)
          - This is the profile page of a user. It allows the user to reset his/her email, username, and password. Resetting password is optional. The user must fill in all required fields.  Email and username are prefilled. 

  - [x] \(11/09/2021) User will be able to edit their profile
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/Profile.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/30
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/141047335-a20a96df-23a4-4711-bc89-3f99a8f85b06.png)
          - A user can reset his/her password and username and email, as long as the username and email are both available.

- Milestone 2
- Milestone 3
  - [ ] \(mm/dd/yyyy of completion) User will be able to purchase items in their Cart
    -  List of Evidence of Feature Completion
      - Status: Pending (Completed, Partially working, Incomplete, Pending)
      - Direct Link: (Direct link to the file or files in heroku prod for quick testing (even if it's a protected page))
      - Pull Requests
        - PR link #1 (repeat as necessary)
      - Screenshots
        - Screenshot #1 (paste the image so it uploads to github) (repeat as necessary)
          - Screenshot #1 description explaining what you're trying to show

  - [ ] \(mm/dd/yyyy of completion) Order Confirmation Page
    -  List of Evidence of Feature Completion
      - Status: Pending (Completed, Partially working, Incomplete, Pending)
      - Direct Link: (Direct link to the file or files in heroku prod for quick testing (even if it's a protected page))
      - Pull Requests
        - PR link #1 (repeat as necessary)
      - Screenshots
        - Screenshot #1 (paste the image so it uploads to github) (repeat as necessary)
          - Screenshot #1 description explaining what you're trying to show

  - [ ] \(mm/dd/yyyy of completion) User will be able to see their Purchase History
    -  List of Evidence of Feature Completion
      - Status: Pending (Completed, Partially working, Incomplete, Pending)
      - Direct Link: (Direct link to the file or files in heroku prod for quick testing (even if it's a protected page))
      - Pull Requests
        - PR link #1 (repeat as necessary)
      - Screenshots
        - Screenshot #1 (paste the image so it uploads to github) (repeat as necessary)
          - Screenshot #1 description explaining what you're trying to show

  - [ ] \(mm/dd/yyyy of completion) Store Owner will be able to see all Purchase History
    -  List of Evidence of Feature Completion
      - Status: Pending (Completed, Partially working, Incomplete, Pending)
      - Direct Link: (Direct link to the file or files in heroku prod for quick testing (even if it's a protected page))
      - Pull Requests
        - PR link #1 (repeat as necessary)
      - Screenshots
        - Screenshot #1 (paste the image so it uploads to github) (repeat as necessary)
          - Screenshot #1 description explaining what you're trying to show

- Milestone 4
### Intructions
#### Don't delete this
1. Pick one project type
2. Create a proposal.md file in the root of your project directory of your GitHub repository
3. Copy the contents of the Google Doc into this readme file
4. Convert the list items to markdown checkboxes (apply any other markdown for organizational purposes)
5. Create a new Project Board on GitHub
   - Choose the Automated Kanban Board Template
   - For each major line item (or sub line item if applicable) create a GitHub issue
   - The title should be the line item text
   - The first comment should be the acceptance criteria (i.e., what you need to accomplish for it to be "complete")
   - Leave these in "to do" status until you start working on them
   - Assign each issue to your Project Board (the right-side panel)
   - Assign each issue to yourself (the right-side panel)
6. As you work
  1. As you work on features, create separate branches for the code in the style of Feature-ShortDescription (using the Milestone branch as the source)
  2. Add, commit, push the related file changes to this branch
  3. Add evidence to the PR (Feat to Milestone) conversation view comments showing the feature being implemented
     - Screenshot(s) of the site view (make sure they clearly show the feature)
     - Screenshot of the database data if applicable
     - Describe each screenshot to specify exactly what's being shown
     - A code snippet screenshot or reference via GitHub markdown may be used as an alternative for evidence that can't be captured on the screen
  4. Update the checklist of the proposal.md file for each feature this is completing (ideally should be 1 branch/pull request per feature, but some cases may have multiple)
    - Basically add an x to the checkbox markdown along with a date after
      - (i.e.,   - [x] (mm/dd/yy) ....) See Template above
    - Add the pull request link as a new indented line for each line item being completed
    - Attach any related issue items on the right-side panel
  5. Merge the Feature Branch into your Milestone branch (this should close the pull request and the attached issues)
    - Merge the Milestone branch into dev, then dev into prod as needed
    - Last two steps are mostly for getting it to prod for delivery of the assignment 
  7. If the attached issues don't close wait until the next step
  8. Merge the updated dev branch into your production branch via a pull request
  9. Close any related issues that didn't auto close
    - You can edit the dropdown on the issue or drag/drop it to the proper column on the project board
