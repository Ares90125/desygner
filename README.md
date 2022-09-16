# Desygner Image Portal

## Acceptance Criteria

There are 3 roles here

- Administrator
- FrontEnd Developer
- User
#### Administrator
Given I am an administrator
When I want to add a new image to the “Stock” tab from an URL or direct upload
Then I should be able to add such image into the system by direct upload or by providing the source URL of the image
And have the ability to specify multiple tags and name of the photo provider

#### FrontEnd Developer
Given I am a front-end developer
When I want to develop the feature of letting users search images from different stock providers
Then I need to have an API endpoint that accepts a search term and a provider (optionally)
And the endpoint should return all images related to the search term (tag) from all providers or just the requested ones accordingly

#### User
Given I am a user
When I search for an image on the stock tab and click on this image
Then this image should be added to my personal library


## Getting Started

1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/)
2. Run `docker compose build --pull --no-cache` to build fresh images
3. Run `docker compose up` (the logs will be displayed in the current shell)
4. Run `php bin/console doctrine:database:create`
5. Run `php bin/console doctrine:schema:create`
6. Run `php bin/console doctrine:fixtures:load`

Then the following mock users are created
    - Admininstrators
        - admin1@d.com---123123123
        - admin2@d.com---123123123
    - Frontend Developers
        - developer1@d.com---123123123
        - developer2@d.com---123123123
    - Users
        - user1@d.com---123123123
        - user2@d.com---123123123
        - ...
        - user10@d.com---123123123

7. Run `yarn install`
8. Run `yarn watch`
9. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
10. Run `docker compose down --remove-orphans` to stop the Docker containers.

## Test
1. Run `php bin/console --env=test doctrine:database:create`
2. Run `php bin/console --env=test doctrine:schema:create`
3. Run `php bin/phpunit`

## Screenshots

#### Administrator Dashboard
![image](https://user-images.githubusercontent.com/50335943/190648587-8a7417a6-f98d-42a9-96ac-cf2a5e07bbae.png)
Administrator can upload images to the platform, switching url mode or local image upload.

On the right plane, images will be displayed, which is uploaded by the current administrator.

#### Developer Dashboard
![image](https://user-images.githubusercontent.com/50335943/190649166-06fe3463-1e64-44e4-8c60-0727834f539d.png)
Front end developers can search images from different providers. Search query will be done debounced as you type on the search input.

#### User Dashboard
![image](https://user-images.githubusercontent.com/50335943/190649504-3d650b68-f8e0-4f03-9883-f8922c614672.png)
User dashboard looks similar to developer's dashboard.

On the left panel, the user can search image. And click the save button on the top right of the image card to save in the personal library

On the right panel, the current user's library images will be displayed as the user save the images on the left.


