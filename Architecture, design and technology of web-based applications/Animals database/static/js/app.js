var app = angular.module('AnimalRegisterApp', ['datetimepicker']);


app.controller('AnimalRegisterController', function($scope, $http) {

    /* Regexes */

    // Used to validate client data

    // Animal name must be 2 to 64 symbols long, start with a letter, allowed symbols are ' and -
    $scope.regexName     = '\\w[ \\w\\-\\\']{1,63}';
    // Species name must be 2 to 64 symbols long, start with a letter, allowed symbols are ' and -
    $scope.regexSpecies  = '\\w[ \\w\\-\\\']{1,63}';
    // Location name must be 2 to 64 symbols long, start with a letter, allowed symbol are numbers, ' and -
    $scope.regexLocation = '\\w[ \\w\\-\\\'0-9]{1,63}';
    // Datetime format is Day-Month-FullYear Hours:Minutes
    $scope.regexDatetime = '(\\d{2}\\.){2}\\d{4} \\d{2}:\\d{2}';


    /* Internal resource wrapper class
     * Provides CRUD+List operations
     *
     * Create  :  POST    /resource/:name
     * Read    :  GET     /resource/:name
     * Update  :  PUT     /resource/:name
     * Delete  :  DELETE  /resource/:name
     * List    :  POST    /resource
     *
     * Declared in controller scope in order to bind the $http variable
     */

    class Resource {

        constructor(url) {
            this.url = url;
        }

        // Parameters cbSuccess and cbFailure in methods below are callback functions

        add(id, data, cbSuccess, cbFailure) {
            $http({
                method  : 'POST',
                url     : `${this.url}/${id}`,
                data    :  data
            }).then(cbSuccess, cbFailure);
        }

        get(id, cbSuccess, cbFailure) {
            $http({
                method  : 'GET',
                url     : `${this.url}/${id}`
            }).then(cbSuccess, cbFailure);
        }

        modify(id, data, cbSuccess, cbFailure) {
            $http({
                method  : 'PUT',
                url     : `${this.url}/${id}`,
                data    :  data
            }).then(cbSuccess, cbFailure);
        }

        remove(id, cbSuccess, cbFailure) {
            $http({
                method  : 'DELETE',
                url     : `${this.url}/${id}`
            }).then(cbSuccess, cbFailure);
        }

        list(criteria, cbSuccess, cbFailure) {
            $http({
                method  : 'POST',
                url     :  this.url,
                data    :  criteria
            }).then(cbSuccess, cbFailure);
        }
    }


    /* Resources */

    let AnimalResource   = new Resource('/animals');
    let SightingResource = new Resource('/sightings');


    /* Data initialization */

    $scope.activeTab = 'animal';

    $scope.animalInfoControlStatus = 'new';

    /* Functionality */

    $scope.datetimepickerOptions = {
        locale  : 'en',
        format  : 'DD.MM.YYYY HH:mm',
        maxDate :  new Date()
    };

    $scope.toClientDatetime = function(datetime) {
        return moment(datetime).format($scope.datetimepickerOptions.format);
    };

    $scope.toServerDatetime = function(datetime) {
        return moment(datetime, $scope.datetimepickerOptions.format).format('YYYY-MM-DD HH:mm:ss');
    };

    /* Animal Control Functions */

    $scope.resetAnimalInfoControl = function() {
        $scope.animalInfoName           = '';
        $scope.animalInfoSpecies        = '';
        $scope.animalInfoNameRevert     = '';
        $scope.animalInfoSpeciesRevert  = '';
        $scope.animalInfoControlStatus  = 'new';
        $scope.animalSightings          = [];
    };

    $scope.addAnimal = function() {
        if($scope.animalInfoControl.$valid) {
            AnimalResource.add(
                $scope.animalInfoName,
                {
                    species: $scope.animalInfoSpecies
                },
                function success(res) {
                    if(res.status == 204) {
                        $scope.loadAnimalInformation($scope.animalInfoName);
                    } else {
                       //TODO: Handle failed operation
                    }
                },
                function failure(res) {
                   //TODO: Handle failed request
                }
            );
        } else {
            //TODO: Handle invalid animal information
        }
    };

    $scope.removeAnimal = function() {
        AnimalResource.remove(
            $scope.animalInfoName,
            function success(res) {
                if(res.status == 204) {
                    $scope.resetAnimalInfoControl();
                } else {
                   //TODO: Handle failed operation
                }
            },
            function failure(res) {
               //TODO: Handle failed request
            }
        );
    };

    $scope.beginModifyAnimal = function() {
        $scope.animalInfoNameRevert     = $scope.animalInfoName;
        $scope.animalInfoSpeciesRevert  = $scope.animalInfoSpecies;
        $scope.animalInfoControlStatus  = 'modify';
    };

    $scope.acceptModifyAnimal = function() {
        if($scope.animalInfoControl.$valid) {
            AnimalResource.modify(
                $scope.animalInfoNameRevert,
                {
                    name    : $scope.animalInfoName,
                    species : $scope.animalInfoSpecies
                },
                function success(res) {
                    if(res.status == 204) {
                        $scope.loadAnimalInformation($scope.animalInfoName);
                    } else {
                        //TODO: Handle failed operation
                    }
                },
                function failure(res) {
                    //TODO: Handle failed request
                }
            );
            $scope.animalInfoControl.status = 'review';
        } else {
            //TODO: Handle invalid animal information
        }
    };

    $scope.cancelModifyAnimal = function() {
        $scope.animalInfoName           = $scope.animalInfoNameRevert;
        $scope.animalInfoSpecies        = $scope.animalInfoSpeciesRevert;
        $scope.animalInfoControlStatus  = 'review';
    };

    $scope.loadAnimalInformation = function(animalName) {
        AnimalResource.get(
            animalName,
            function success(res) {
                if(res.status == 200) {
                    $scope.activeTab = 'animal';
                    $scope.animalInfoName            =  res.data.name;
                    $scope.animalInfoSpecies         =  res.data.species;
                    $scope.animalInfoControlStatus   = 'review';
                    $scope.animalSightings           =  [];
                    SightingResource.list(
                        res.data,
                        function success(res) {
                            if(res.status == 200) {
                                $scope.animalSightings = res.data;
                            } else {
                                $scope.animalSightings = [];
                            }
                            for(let i = 0; i < $scope.animalSightings.length; i++) {
                                let datetime = $scope.animalSightings[i].datetime;
                                $scope.animalSightings[i].datetime = $scope.toClientDatetime(datetime);
                                $scope.animalSightings[i].status   = 'review';
                            }
                            $scope.animalSightings.push({
                                    status   : 'new',
                                    location : '',
                                    datetime : ''
                            });
                        },
                        function failure(res) {
                            //TODO: Handle failed operation
                        }
                    );
                } else {
                    $scope.resetAnimalInfoControl();
                }
            },
            function failure(res) {
                //TODO: Handle failed request
            }
        );
    };

    /* Sighting Control Functionality */

    $scope.addSighting = function(control, idx) {
        if(control.$valid) {
            SightingResource.add(
                0,  // Using non-existent index to add sighting
                {
                    name     : $scope.animalInfoName,
                    location : $scope.animalSightings[idx].location,
                    datetime : $scope.toServerDatetime($scope.animalSightings[idx].datetime)
                },
                function success(res) {
                    if(res.status == 204) {
                        $scope.loadAnimalInformation($scope.animalInfoName);
                    } else {
                        //TODO: Handle failed operation
                    }
                },
                function failure(res) {
                    //TODO: Handle failed request
                }
            );
        } else {
            //TODO: Handle invalid animal information
        }
    };

    $scope.removeSighting = function(idx) {
        SightingResource.remove(
            $scope.animalSightings[idx].id,
            function success(res) {
                if(res.status == 204) {
                    $scope.loadAnimalInformation($scope.animalInfoName);
                } else {
                    //TODO: Handle failed operation
                }
            },
            function failure(res) {
                //TODO: Handle failed request
            }
        );
    };

    $scope.beginModifySighting = function(idx) {
        $scope.animalSightings[idx].status = 'modify';
        $scope.animalSightings[idx].locationRevert = $scope.animalSightings[idx].location;
        $scope.animalSightings[idx].datetimeRevert = $scope.animalSightings[idx].datetime;
    };

    $scope.acceptModifySighting = function(control, idx) {
        if(control.$valid) {
            SightingResource.modify(
                $scope.animalSightings[idx].id,
                {
                    location: $scope.animalSightings[idx].location,
                    datetime: $scope.toServerDatetime($scope.animalSightings[idx].datetime)
                },
                function success(res) {
                    if(res.status == 204) {
                        $scope.loadAnimalInformation($scope.animalInfoName);
                    } else {
                        //TODO: Handle failed operation
                    }
                },
                function failure(res) {
                    //TODO: Handle failed request
                }
            );
            $scope.animalSightings[idx].status = 'review';
        } else {
            //TODO: Handle invalid sighting information
        }
    };

    $scope.cancelModifySighting = function(idx) {
        $scope.animalSightings[idx].status   = 'review';
        $scope.animalSightings[idx].location = $scope.animalSightings[idx].locationRevert;
        $scope.animalSightings[idx].datetime = $scope.animalSightings[idx].datetimeRevert;
    };

    /* Species Control Functionality */

    $scope.loadSpeciesInformation = function(species) {
        AnimalResource.list(
            {
                species: species
            },
            function success(res) {
                if(res.status == 200) {
                    $scope.activeTab = 'species';
                    $scope.animalsOfSpecies = res.data;
                    for(let i = 0; i < $scope.animalsOfSpecies.length; i++) {
                        SightingResource.list(
                            $scope.animalsOfSpecies[i],
                            function success(res) {
                                if(res.status == 200) {
                                    let location = res.data[0].location;
                                    let recent = moment(res.data[0].datetime);
                                    for(let j = 1; j < res.data.length; j++) {
                                        let current = moment(res.data[j].datetime);
                                        if(current.isAfter(recent)) {
                                            recent = current;
                                            location = res.data[j].location;
                                        }
                                    }
                                    $scope.animalsOfSpecies[i].datetime = $scope.toClientDatetime(recent);
                                    $scope.animalsOfSpecies[i].location = location;
                                } else {
                                    //TODO: Handle failed operation
                                }
                            },
                            function failure(res) {
                                //TODO: Handle failed request
                            }
                        );
                    }
                } else {
                    $scope.animalsOfSpecies = [];
                }
            },
            function failure(res) {
                //TODO: Handle invalid species information
            }
        );
    };

    /* Location Control Functionality */

    $scope.loadLocationInformation = function(location) {
        SightingResource.list(
            {
                location: location
            },
            function success(res) {
                if(res.status == 200) {
                    $scope.activeTab = 'location';
                    $scope.animalsAtLocation = res.data;
                    for(let i = 0; i < $scope.animalsAtLocation.length; i++) {
                        let datetime = $scope.animalsAtLocation[i].datetime;
                        $scope.animalsAtLocation[i].datetime = $scope.toClientDatetime(datetime);
                        AnimalResource.get(
                            $scope.animalsAtLocation[i].name,
                            function success(res) {
                                if(res.status == 200) {
                                    $scope.animalsAtLocation[i].species = res.data.species;
                                } else {
                                    //TODO: Handle failed operation
                                }
                            },
                            function failure(res) {
                                //TODO: Handle failed request
                            }
                        );
                    }
                } else {
                    $scope.animalsAtLocation = [];
                }
            },
            function failure(res) {
                //TODO: Handle invalid species information
            }
        );
    };

});
