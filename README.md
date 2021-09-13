# symfony-time-example

### Quickstart

run `bin/init_all.sh` to run docker-compose with all required containers, install dependencies and execute database migrations.
Requires docker, docker-compose to be installed on the host system.
*Init script likely has to run more than once if mysql takes too long to boot up*

The webinterface will then be available through `http://localhost:8000`

### Structure

 * `/bin` binary files from symfony + init_all.sh helper file
 * `/config` symfony configuration dir
 * `/docker` infrastructure files to facilitate docker environment
 * `/migrations` storage folder for doctrine migration scripts
 * `/public` entrypoint for webserver
 * `/src` sources root
    * `/DataFixtures`
    * `/Framework` general-purpose logic to facilitate non-domain application flow
    * `/TimeTracking` domain root for this application
        * `/ArgumentValueResolver` Helper classes to generate objects that are typehinted in controller arguments
        * `/Command` Command dtos
        * `/Controller` Domain-specific controllers to wire up frontend to business-logic
        * `/Exception` Domain-specific exceptions
        * `/Handler` Command- and Queryhandlers, one for each
        * `/Listener` Symfony EventListener to control business-logic
        * `/Query` Query dtos
        * `/Report` Special group for helpers regarding Report generation
 * `/templates` storage folder for twig templates
 * `/tests` PHPUnit Tests root
 * `/translations` [unused]
 
### General Flow
 
To Route Data between the frontend layer and the Businesslogic layer, Commands and Queries are used. A command sends wrapped data to a specific domain handler and may or may not return a value.
Queries always return data and abstract the sender from the way how and where data is fetched from. Any Service or Controller can fetch data
Usually the application would also contain Events through an EventBus, which was not implemented due to time constraints.
Furthermore, Redis is present and configured as a message transport but no messagebus has been designed or tested at this point in time to function via async messagebus.
 
The app is not tested to run in prod mode.
