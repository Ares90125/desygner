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
        - developer1@d.com
        - developer2@d.com
    - Users
        - user1@d.com
        - user2@d.com
        - ...
        - user10@d.com

7. Run `yarn install`
8. Run `yarn watch`
4. Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)
5. Run `docker compose down --remove-orphans` to stop the Docker containers.

## Test
4. Run `php bin/console --env=test doctrine:database:create`
5. Run `php bin/console --env=test doctrine:schema:create`
7. Run `php bin/phpunit`

