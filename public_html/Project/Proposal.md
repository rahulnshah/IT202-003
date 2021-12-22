# Project Name: Simple Shop
## Project Summary: This project will create a simple e-commerce site for users. Administrators or store owners will be able to manage inventory and users will be able to manage their cart and place orders.
## Github Link: https://github.com/rahulnshah/IT202-003/tree/prod
## Project Board Link: https://github.com/rahulnshah/IT202-003/projects/1
## Project Demo: https://mediaspace.njit.edu/media/IT202ShopProject2021Demo/1_k6tsvkbb
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
  - [x] \(11\17\2021) User with an admin role or shop owner role will be able to add products to inventory
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/admin/add_product.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/39
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/143724146-5c3260bb-c139-49bf-ba0e-c3930b1c551f.png)
          - Admin/Shop owner can fill in form to add a product to the Products table. 
        ![image](https://user-images.githubusercontent.com/68120349/143923438-3f271ca0-8290-43b6-98d2-2a9b6931d02d.png)
          - Products table
        ![image](https://user-images.githubusercontent.com/68120349/144722430-fe98539f-067f-482c-8826-bd252bb41624.png)
          - Cart table

  - [x] \(11\17\2021) Any user will be able to see products with visibility = true on the Shop page
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: http://rns22-prod.herokuapp.com/Project/shop.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/39
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/143724181-0183d404-c40f-434d-b116-119822471891.png)
          - A user need not be logged in to see products with visibility = true (or integer 1, in this case) 
        ![image](https://user-images.githubusercontent.com/68120349/143724722-bada2c81-7677-41b0-82ac-448a2aeaa7f1.png)
          - User can filter by categories
        ![image](https://user-images.githubusercontent.com/68120349/143724738-c7e95f63-fd9b-4391-aea8-26abc33ff5e4.png)
          - Sort products by their unit price.
        ![image](https://user-images.githubusercontent.com/68120349/143724759-ac5ceef8-e0d9-4e7a-a950-04b916c1d808.png)
          - User can search an item by name
          
  - [x] \(11\17\2021) Admin/Shop owner will be able to see products with any visibility
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/admin/list_products.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/39
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/143724242-055e832e-a3d5-4b69-a5c4-71cf80cb0829.png)
          - Admin/Shop owner can view all products, regardless of visibility value of each product.
          
  - [x] \(11\17\2021) Admin/Shop owner will be able to edit any product
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/admin/edit_product.php?id=1
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/39
        - https://github.com/rahulnshah/IT202-003/pull/40
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/143724301-fffed5e0-e47c-4eb7-bde6-0539e354819e.png)
          - Admin/Shop owner will be able to edit any properties of a product
           
  - [x] \(11\18\2021) User will be able to click an item from a list and view a full page with more info about the item (Product Details Page)
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/product_details.php?id=3
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/40
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/143724413-f583177b-18bf-4b5d-9cec-0cbde7f90c46.png)
          - User will be able to click an item from a list and view a full page with more info about the item (Product Details Page)
           
  - [x] \(11\26\2021) User must be logged in for any Cart related activity below
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/cart.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/54
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/143724426-042654b1-7f05-4cff-86e9-09062062f937.png)
          - User must be logged in for any Cart related activity. Otherwise, a friendly message is thrown. 
           
  - [x] \(11\26\2021) User will be able to add items to Cart
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/shop.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/54
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/143724454-c24d06c8-cbe2-4d8c-8676-422f6d77b3fb.png)
![image](https://user-images.githubusercontent.com/68120349/143724457-25e76aa3-8921-4460-9668-1829414322c6.png)
          - Example of adding an item to cart.
           
  - [x] \(11\26\2021) User will be able to see their cart
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/cart.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/54
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/143724500-e241a2c8-17e6-44ec-89ba-e719bc1eb528.png)
          - User will be able to see their cart 
           
  - [x] \(11\26\2021) User will be able to change quantity of items in their cart
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/cart.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/54
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/143724518-a822dd7f-9d8c-43f8-bb25-128235f443b4.png)
          - User will be able to change quantity of items in their cart
        ![image](https://user-images.githubusercontent.com/68120349/143724538-6f5c3447-bd93-4851-a697-9abb6d829845.png)
          - After refreshing page.
           
  - [x] \(11\26\2021) User will be able to remove a single item from their cart via button click
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/cart.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/54
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/143724559-845e095a-d797-4e53-8169-6dd4f19c0a8e.png)
          - User will be able to remove a single item from their cart via clicking on the "Remove" button.
        ![image](https://user-images.githubusercontent.com/68120349/143724588-d128cd56-b3b4-4c1b-b743-f6d79e592e9a.png)
          - After refreshing the page.
           
  - [x] \(11\26\2021) User will be able to clear their entire cart via a button click
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/cart.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/54
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/143724598-87783787-9cda-4eb2-a5a9-17fbd41c2ad5.png)
          - User can clear cart by clicking on the "Clear cart" button 
  ## ⭐Extra Credit Features⭐
  - [x] \(11\26\2021) User will be able to view products within different price ranges 
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/shop.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/54
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/143724659-04c88233-2f84-4638-930b-dab65bd4c71c.png)
          - Shop page dynamically generates a range of prices under which all products with visibility of 1 fall.
    
  - [x] \(11\29\2021) Added jQuery to Shop Project.
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/shop.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/58
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/143903101-de677b68-bc3e-4f63-a5f2-017e271e98ff.png)
          -  User will see a red border upon blur in all form fields with type=text, password, and email and if those fields are left blank.
        ![image](https://user-images.githubusercontent.com/68120349/143903342-e240055d-8633-4f56-90b8-093b413fd4e0.png)
          - Added jQuery on hover on the Admin dropdown menu.
        ![image](https://user-images.githubusercontent.com/68120349/143903571-97633c5d-8658-4d80-ac33-d57c7511a10f.png)
          - User will be able click on the body of a card and card will have a blue border generated around it upon hover.
- Milestone 3
  - [x] \(12/07/2021) User will be able to purchase items in their Cart
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/cart.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/77
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/145510140-ca05614d-6618-4e13-a0ff-b12c7b25c766.png)
          - Orders table
        ![image](https://user-images.githubusercontent.com/68120349/145510221-2b40affc-0b3f-48d0-911e-9f0a8d687fab.png)
          - OrderItems table
        ![image](https://user-images.githubusercontent.com/68120349/145510259-e47dbdb3-9116-433e-b866-4004c93b8eaa.png)
          - Flash messages show up when checkout.php's client side validation fails.
        ![image](https://user-images.githubusercontent.com/68120349/145510911-a292585d-92e9-4769-8026-061c454919b7.png)
          - Flash message shows up when user cannot place order.=, either because product unit_price does not match with an item's unit_price in the cart table or an item's desired quantity is greater than its stock in the Products table.

  - [x] \(12/09/2021) Order Confirmation Page
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/confirmation_page.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/79
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/145511072-e73ea341-1cda-467a-85c3-83de583b710c.png)
          - User sees their placed order after checkout.

  - [x] \(12/09/2021) User will be able to see their Purchase History
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/orders.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/78
        - https://github.com/rahulnshah/IT202-003/pull/81
        - https://github.com/rahulnshah/IT202-003/pull/80
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/145511277-551868d3-a618-4eb7-998f-a54cab8e1785.png)
          - A user's history of purchases.

  - [x] \(12/09/2021) Store Owner will be able to see all Purchase History
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/admin/confirmation_page.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/81
        - https://github.com/rahulnshah/IT202-003/pull/80
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/145511497-1e34066a-3498-4537-8c58-6249ce8c6ace.png)
          - Store Owner/Admin can see every user's order.
        ![image](https://user-images.githubusercontent.com/68120349/145511606-d05a76d8-febd-4285-add3-868da88d251b.png)
          - User can see order items of a single order by clicking View on an order in orders.php and so can the Store Owner/Admin.
## ⭐Extra Credit Features⭐
  - [x] \(12\10\2021) User will be able to view a percent of change in price of a product.
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/cart.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/89
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/145623689-4fe7fca0-47c0-4f55-a37e-5778e68539e9.png)
          - User can now see a percentage of change in price of a product in his/her cart.
- Milestone 4
  - [x] \(12/20/2021) User can set their profile to be public or private (will need another column in Users table)
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/Profile.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/101
        - https://github.com/rahulnshah/IT202-003/pull/112
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/146988027-afd96a83-189d-45da-a7dc-5d370079255c.png)
          - User can set their profile to be public or private
        ![image](https://user-images.githubusercontent.com/68120349/146988504-9f47f286-a47a-435a-91be-54af2a07a472.png)
          - Profile is public but email is not shown. 


  - [x] \(12/17/2021) User will be able to rate a product they purchased
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/product_details.php?id=1
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/95
        - https://github.com/rahulnshah/IT202-003/pull/114
        - https://github.com/rahulnshah/IT202-003/pull/121
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/146993555-d0ad4dbc-7fc6-48a7-bde3-be002a8e808f.png)
          - Ratings table
        ![image](https://user-images.githubusercontent.com/68120349/146988795-260dda93-3ebe-4061-9443-e220d6310608.png)
          - User will be able to rate a product they purchased. 

  - [x] \(12/19/2021) User’s Purchase History Changes
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/orders.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/96
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/146989309-5758a27a-32ed-40cf-ae51-2ca5b80e7525.png)
          - User can Filter by date range, Filter by category, Sort by total, date purchased on their orders.


  - [x] \(12/19/2021) Store Owner Purchase History Changes
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/admin/list_purchase_history.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/97
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/146989545-675cc3d5-4b98-4939-9fa2-348e8964e5d3.png)
          - Store Owner can Filter by date range, Filter by category, Sort by total, date purchased on their orders.

  - [x] \(12/21/2021) Add pagination to Shop Page (and any other product lists not yet mentioned)
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/shop.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/102
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/146989672-55f33583-c97a-49b1-b085-9bed6433cfd1.png)
          - Pagination on shop.php.
        ![image](https://user-images.githubusercontent.com/68120349/146989841-960422a9-b8ca-4804-afd0-697dc85c5489.png)
          - Pagination on list_products.php.

  - [x] \(12/20/2021) Store Owner will be able to see all products out of stock
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/admin/list_products.php
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/99
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/146990270-70aba8f8-c96f-4201-8414-610112eff123.png)
          -  Store Owner will be able to see all products out of stock (quantity <= stock).

  - [x] \(12/20/2021) User can sort products by average rating on the Shop Page
    -  List of Evidence of Feature Completion
      - Status: Completed
      - Direct Link: https://rns22-prod.herokuapp.com/Project/product_details.php?id=1
      - Pull Requests
        - https://github.com/rahulnshah/IT202-003/pull/100
        - https://github.com/rahulnshah/IT202-003/pull/102
      - Screenshots
        ![image](https://user-images.githubusercontent.com/68120349/146993674-d943a504-2455-4f41-a076-570bdda02623.png)
          - Products table with average_rating column.
        ![image](https://user-images.githubusercontent.com/68120349/146990430-3d391b13-0282-4c7b-80d1-0873c118bda9.png)
          - User can sort products by average rating on the Shop Page

## ⭐Extra Credit Features⭐
- [x] \(12\21\2021) User will be able to view results through a limit.
  -  List of Evidence of Feature Completion
    - Status: Completed
    - Direct Link: https://rns22-prod.herokuapp.com/Project/shop.php
    - Pull Requests
      - https://github.com/rahulnshah/IT202-003/pull/102
    - Screenshots
      ![image](https://user-images.githubusercontent.com/68120349/146992144-0ffd7ec2-dfd9-4813-8654-5f6a5043a1aa.png)
        - Limit results to 1 per page on shop.php.
      ![image](https://user-images.githubusercontent.com/68120349/146992347-3f49b710-9f67-4abe-9d4b-ec4fc734b213.png)
        - Limit results to 2 per page on orders.php.


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
