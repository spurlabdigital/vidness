import React, { Component } from 'react';
import { Video360 } from '@uscsf/react-native-video360';


/*
Steps to add a video to the app
1. Create screenshot preview
2. Add file to archive
3. Add ViroSphere
4. Add video to state in Explore
*/

import {
	VirosceneNavigator,
	ViroSceneNavigator,
	ViroNode,
	ViroARScene,
	ViroAmbientLight,
	Viro360Video,
	ViroAnimations,
	Viro360Image,
	ViroUtils,
	Viro3DObject,
	ViroARTrackingTargets,
	ViroARImageMarker,
    ViroMaterials,
	ViroARPlaneSelector,
    ViroVideo,
    ViroSphere
} from '@viro-community/react-viro';

import CompassHeading from 'react-native-compass-heading';

const geolib = require('geolib');

ViroMaterials.createMaterials({
    material1: {
        lightingModel: "PBR",
        diffuseTexture: require('../media/objects/1-skin.jpg'),
    },
    material2: {
        lightingModel: "PBR",
        diffuseTexture: require('../media/objects/2-skin.jpg'),
    },
    material3: {
        lightingModel: "PBR",
        diffuseTexture: require('../media/objects/3-skin.jpg'),
    },
    material4: {
        lightingModel: "PBR",
        diffuseTexture: require('../media/objects/4-skin.jpg'),
    },
    material5: {
        lightingModel: "PBR",
        diffuseTexture: require('../media/objects/5-skin.jpg'),
    },
    material6: {
        lightingModel: "PBR",
        diffuseTexture: require('../media/objects/6-skin.jpg'),
    },
    material7: {
        lightingModel: "PBR",
        diffuseTexture: require('../media/objects/7-skin.jpg'),
    },
    material8: {
        lightingModel: "PBR",
        diffuseTexture: require('../media/objects/8-skin.jpg'),
    },
    material9: {
        lightingModel: "PBR",
        diffuseTexture: require('../media/objects/9-skin.jpg'),
    },
    material10: {
        lightingModel: "PBR",
        diffuseTexture: require('../media/objects/10-skin.jpg'),
    },
    material11: {
        lightingModel: "PBR",
        diffuseTexture: require('../media/objects/11-skin.jpg'),
    },
    material12: {
        lightingModel: "PBR",
        diffuseTexture: require('../media/objects/12-skin.jpg'),
    },
    material13: {
        lightingModel: "PBR",
        diffuseTexture: require('../media/objects/13-skin.jpg'),
    },
    material14: {
        lightingModel: "PBR",
        diffuseTexture: require('../media/objects/14-skin.jpg'),
    },
    material15: {
        lightingModel: "PBR",
        diffuseTexture: require('../media/objects/15-skin.jpg'),
    },
    material16: {
        lightingModel: "PBR",
        diffuseTexture: require('../media/objects/16-skin.jpg'),
    },
    material17: {
        lightingModel: "PBR",
        diffuseTexture: require('../media/objects/17-skin.jpg'),
    },
    material18: {
        lightingModel: "PBR",
        diffuseTexture: require('../media/objects/18-skin.jpg'),
    },
    material19: {
        lightingModel: "PBR",
        diffuseTexture: require('../media/objects/19-skin.jpg'),
    },
    material20: {
        lightingModel: "PBR",
        diffuseTexture: require('../media/objects/20-skin.jpg'),
    },
    material23: {
        lightingModel: "PBR",
        diffuseTexture: require('../media/objects/23-skin.jpg'),
    },
    material24: {
        lightingModel: "PBR",
        diffuseTexture: require('../media/objects/24-skin.jpg'),
    }
});
// ViroARTrackingTargets.createTargets({
// 	markerLageplan: {
//         source : require('../media/markers/marker-lageplan.png'),
// 		orientation : "Up",
// 		physicalWidth : 0.45
//     }
// });

ViroAnimations.registerAnimations({
	scaleUp:{
		properties:{scaleX:1, scaleY:1, scaleZ:1,},
		duration: 500, easing: "bounce"
	},
    scaleCar:{properties:{scaleX:.1, scaleY:.1, scaleZ:.1,},
        duration: 500, easing: "bounce"},
});

// import lagertorVideoReference from '../media/videos/5_Lagertor_RB_Innen.mp4';


var createReactClass = require('create-react-class');

var MainSceneAR = createReactClass({
    
    getInitialState: function() {
        return {
            playAnim: false,
            initialized: false,
            internalPlayingVideoSource: null,
            internalLagertorVideoPreviewVisible: false,

            internalFrontGardenMarkerVisible: true,
            
            markerLat1: 43.85654620951612, //53.19217030506924,
            markerLng1: 18.40337889383154, //13.16270606624991,
            position1: [0, 0, 0],

            markerLat2: 43.85639341710899, // 53.191786,
            markerLng2: 18.403392304875872, //13.163884,
            position2: [0, 0, 0],

            markerLat3: 43.85649979161105, // 53.191734,
            markerLng3: 18.403625657047296, // 13.164126,
            position3: [0, 0, 0],

            markerLat4: 53.19139803296096,
            markerLng4: 13.16383630334163,
            position4: [0, 0, 0],

            markerLat5: 53.19193477725392, 
            markerLng5: 13.164643648246507,
            position5: [0, 0, 0],

            markerLat6: 53.191321, 
            markerLng6: 13.164786,
            position6: [0, 0, 0],

            markerLat7: 53.19116180424104, 
            markerLng7: 13.164575428399191,
            position7: [0, 0, 0],

            markerLat8: 53.191284, 
            markerLng8: 13.165536,
            position8: [0, 0, 0],

            markerLat9: 53.191220, 
            markerLng9: 13.165407,
            position9: [0, 0, 0],

            markerLat10: 53.191278, 
            markerLng10: 13.166167,
            position10: [0, 0, 0],

            markerLat11: 53.190928, 
            markerLng11: 13.165856,
            position11: [0, 0, 0],

            markerLat12: 53.190422172911504, 
            markerLng12: 13.164651686983037,
            position12: [0, 0, 0],

            markerLat13: 53.189523, 
            markerLng13: 13.163739,
            position13: [0, 0, 0],

            markerLat14: 53.189295, 
            markerLng14: 13.164625,
            position14: [0, 0, 0],

            markerLat15: 53.189432, 
            markerLng15: 13.164093,
            position15: [0, 0, 0],

            markerLat16: 53.190768, 
            markerLng16: 13.159618,
            position16: [0, 0, 0],

            markerLat17: 53.190344, 
            markerLng17: 13.163764,
            position17: [0, 0, 0],

            markerLat18: 53.191511, 
            markerLng18: 13.165022,
            position18: [0, 0, 0],

            markerLat19: 53.191072, 
            markerLng19: 13.165070,
            position19: [0, 0, 0],

            markerLat20: 53.187311, 
            markerLng20: 13.139751,
            position20: [0, 0, 0],

            markerLat23: 53.19138, 
            markerLng23: 13.17053,
            position23: [0, 0, 0],

            markerLat24: 53.19131397418224, 
            markerLng24: 13.170807389876368,
            position24: [0, 0, 0],

            // markerLat125: 52.52947076523271, 
            // markerLng125: 13.34975289394119,
            // position125: [0, 0, 0],

            // markerLat126: 52.52938591515881, 
            // markerLng126: 13.349677792092915,
            // position126: [0, 0, 0],

            // markerLat127: 52.52943976234012,
            // markerLng127: 13.34958659699144,
            // position127: [0, 0, 0],

            videoPosition: [0, 0, -4],

            heading: 0,
            tapBlue: false,

            currentUserPosition: {
                latitude: null,
                longitude: null
            },

            lastReportedLatitude: 0,
            lastReportedLongitude: 0,
        }
    },
    _onAnchorFound() {
        this.setState({
            internalFrontGardenMarkerVisible: true,
            playAnim: true,
        })
    },

    // source: https://git.rz.tu-bs.de/cosima.berger/viroreact_geolocation/-/blob/main/js/SceneAR.js
    latLongToMerc(lat_deg, lon_deg) {
        // Latitude / Longitude in radians
        const lon_rad = (lon_deg / 180.0) * Math.PI;
        const lat_rad = (lat_deg / 180.0) * Math.PI;
        
        // Earth radius
        const earth_radius =  6378137.00;
    
        // Convert to meters
        const xmeters  = earth_radius * lon_rad;
        const ymeters = earth_radius * Math.log((Math.sin(lat_rad) + 1) / Math.cos(lat_rad))
        
        // Return meters in Json format
        return ({x:xmeters, y:ymeters});
    },

    _showVideoPreview(videoName, videoType, videoPosition) {
        this.setState({
            videoPosition: videoPosition
        })
        this.props.arSceneNavigator.viroAppProps._externalShowVideoPreview(videoName, videoType);
    },
    _lagertorVideoFinished() {
        
    },

    onInitialized() {
        if(!this.state.initialized) {
            this.setState({initialized: true});
            this.getHeading();
        }
    },

    onInitialized2() {    

        // If the AR Scence is not initialized yet...
        if(!this.state.initialized) {
          
          // Set AR Scence state to initialized
          this.setState({initialized: true})
          // Get the compass heading when the AR scene initializes
          this.getHeading();
        }
    },

    transformPositionToAR(lat, long) {

        // Convert marker and device position into meters
        if (!this.props.sceneNavigator.viroAppProps.currentLocationLatitude || !this.props.sceneNavigator.viroAppProps.currentLocationLongitude) {
            
        }

        let myLat = parseFloat(this.props.sceneNavigator.viroAppProps.currentLocationLatitude);
        let myLong = parseFloat(this.props.sceneNavigator.viroAppProps.currentLocationLongitude);
        const markerPosition = this.latLongToMerc(lat, long);
        const devicePosition = this.latLongToMerc(myLat, myLong);
        
        // console.log("My Position: ", myLat, myLong);
        // console.log("Marker Position: " + lat + ", " + long)
        // console.log("Marker Point Meters: " + markerPosition.x + ", " + markerPosition.y)
        // console.log("Device Point Meters: " + devicePosition.x + ", " + devicePosition.y)
    
        // latitude (north, south) maps to the z axis in AR
        let markerFinalPosZ = markerPosition.y - devicePosition.y;
    
        // longitude(east, west) maps to the x axis in AR
        let markerFinalPosX = markerPosition.x - devicePosition.x;
        
        // For android add rotation by using the heading 
        if(Platform.OS == 'android' || true) {
          const angleRadian = (this.state.heading * Math.PI) / 180;
          const markerRotatedX = markerFinalPosX * Math.cos(angleRadian) - markerFinalPosZ * Math.sin(angleRadian);
          const markerRotatedZ = markerFinalPosZ * Math.cos(angleRadian) + markerFinalPosX * Math.sin(angleRadian);
        
          markerFinalPosX =  markerRotatedX;
          markerFinalPosZ = markerRotatedZ;
        }
        
        //flip the z, as negative z is in front of the device (north) and positive z is behind the device (south).
        return ({x:markerFinalPosX, z:-1*markerFinalPosZ});
    },

    // renderMarkerTimer() {
    //     setInterval(() => {
            
    //     }, 2000);
    // },

    getHeading() {
        const degree_update_rate = 3;
        CompassHeading.start(degree_update_rate, degree => {
            this.setState({
                heading: degree.heading
            });
            // console.warn('Heading ' + degree.heading);
            // only render markers if the distance has increased by more than 2 meters
            
            console.log('Heading was set', this.state.heading);
            try {
                let targetLocation = { latitude: this.state.lastReportedLatitude, longitude: this.state.lastReportedLongitude };
                let distanceFromTarget = geolib.getDistance({ latitude: this.props.sceneNavigator.viroAppProps.currentLocationLatitude, longitude: this.props.sceneNavigator.viroAppProps.currentLocationLongitude }, 
                    targetLocation);
                if (distanceFromTarget > 2) {
                    this.setState({
                        lastReportedLatitude: this.props.sceneNavigator.viroAppProps.currentLocationLatitude,
                        lastReportedLongitude: this.props.sceneNavigator.viroAppProps.currentLocationLongitude
                    });
                    console.log('Markers rendered at timestamp', new Date());
                    this.renderMarker();
                }
            } catch (e) {
                console.log('Error was', e);
            }
            
            // CompassHeading.stop();
        });
    },

    updateMyLocation(location) {
        console.log('My location was updated to', location);
    },

    renderMarker() {
        // this.setState({
        //     currentUserPosition: {
        //         latitude: 53.19178260527261, 
        //         longitude: 13.163720317596562
        //     }
        // });
        // console.log('Current device position was set', this.state.currentUserPosition);

        const markerPoint1 = this.transformPositionToAR(this.state.markerLat1, this.state.markerLng1);
        const markerPoint2 = this.transformPositionToAR(this.state.markerLat2, this.state.markerLng2);
        const markerPoint3 = this.transformPositionToAR(this.state.markerLat3, this.state.markerLng3);
        const markerPoint4 = this.transformPositionToAR(this.state.markerLat4, this.state.markerLng4);
        const markerPoint5 = this.transformPositionToAR(this.state.markerLat5, this.state.markerLng5);
        const markerPoint6 = this.transformPositionToAR(this.state.markerLat6, this.state.markerLng6);
        const markerPoint7 = this.transformPositionToAR(this.state.markerLat7, this.state.markerLng7);
        const markerPoint8 = this.transformPositionToAR(this.state.markerLat8, this.state.markerLng8);
        const markerPoint9 = this.transformPositionToAR(this.state.markerLat9, this.state.markerLng9);
        const markerPoint10 = this.transformPositionToAR(this.state.markerLat10, this.state.markerLng10);
        const markerPoint11 = this.transformPositionToAR(this.state.markerLat11, this.state.markerLng11);
        const markerPoint12 = this.transformPositionToAR(this.state.markerLat12, this.state.markerLng12);
        const markerPoint13 = this.transformPositionToAR(this.state.markerLat13, this.state.markerLng13);
        const markerPoint14 = this.transformPositionToAR(this.state.markerLat14, this.state.markerLng14);
        const markerPoint15 = this.transformPositionToAR(this.state.markerLat15, this.state.markerLng15);
        const markerPoint16 = this.transformPositionToAR(this.state.markerLat16, this.state.markerLng16);
        const markerPoint17 = this.transformPositionToAR(this.state.markerLat17, this.state.markerLng17);
        const markerPoint18 = this.transformPositionToAR(this.state.markerLat18, this.state.markerLng18);
        const markerPoint19 = this.transformPositionToAR(this.state.markerLat19, this.state.markerLng19);
        const markerPoint20 = this.transformPositionToAR(this.state.markerLat20, this.state.markerLng20);
        const markerPoint23 = this.transformPositionToAR(this.state.markerLat23, this.state.markerLng23);
        const markerPoint24 = this.transformPositionToAR(this.state.markerLat24, this.state.markerLng24);
        // const markerPoint125 = this.transformPositionToAR(this.state.markerLat125, this.state.markerLng125);
        // const markerPoint126 = this.transformPositionToAR(this.state.markerLat126, this.state.markerLng126);
        // const markerPoint127 = this.transformPositionToAR(this.state.markerLat127, this.state.markerLng127);

        this.setState({
            position1: [markerPoint1.x, 0, markerPoint1.z],
            position2: [markerPoint2.x, 0, markerPoint2.z],
            position3: [markerPoint3.x, 0, markerPoint3.z],
            position4: [markerPoint4.x, 0, markerPoint4.z],
            position5: [markerPoint5.x, 0, markerPoint5.z],
            position6: [markerPoint6.x, 0, markerPoint6.z],
            position7: [markerPoint7.x, 0, markerPoint7.z],
            position8: [markerPoint8.x, 0, markerPoint8.z],
            position9: [markerPoint9.x, 0, markerPoint9.z],
            position10: [markerPoint10.x, 0, markerPoint10.z],
            position11: [markerPoint11.x, 0, markerPoint11.z],
            position12: [markerPoint12.x, 0, markerPoint12.z],
            position13: [markerPoint13.x, 0, markerPoint13.z],
            position14: [markerPoint14.x, 0, markerPoint14.z],
            position15: [markerPoint15.x, 0, markerPoint15.z],
            position16: [markerPoint16.x, 0, markerPoint16.z],
            position17: [markerPoint17.x, 0, markerPoint17.z],
            position18: [markerPoint18.x, 0, markerPoint18.z],
            position19: [markerPoint19.x, 0, markerPoint19.z],
            position20: [markerPoint20.x, 0, markerPoint20.z],
            position23: [markerPoint23.x, 0, markerPoint23.z],
            position24: [markerPoint24.x, 0, markerPoint24.z],
            // position125: [markerPoint125.x, 0, markerPoint125.z],
            // position126: [markerPoint126.x, 0, markerPoint126.z],
            // position127: [markerPoint127.x, 0, markerPoint127.z],
            videoPosition: this.state.videoPosition
        });
    },
    
    render() {
        return (
		<ViroARScene ref={arScene => { this.arScene = arScene }} onTrackingUpdated={this.onInitialized}>
			<ViroAmbientLight color="#ffffff" intensity={200}/>
            <ViroSphere heightSegmentCount={50}
                animation={{name:"scaleCar", run:this.state.animateCar,}}
                widthSegmentCount={50}
                visible={ this.props.sceneNavigator.viroAppProps.frontGardenMarkerVisible }
                radius={1.0}
                position={ this.state.position1 }
                onClick={() => this._showVideoPreview('1_Matthias_Fuehrerhaus.mp4', '360')}
                materials={["material1"]} />

            <ViroSphere heightSegmentCount={50}
                animation={{name:"scaleCar", run:this.state.animateCar,}}
                widthSegmentCount={50}
                visible={ this.props.sceneNavigator.viroAppProps.frontGardenMarkerVisible }
                radius={1.0}
                position={ this.state.position2 }
                onClick={() => this._showVideoPreview('2_Lagertor_RB_Aussen.mp4', '360')}
                materials={["material2"]} />

            <ViroSphere heightSegmentCount={50}
                animation={{name:"scaleCar", run:this.state.animateCar,}}
                widthSegmentCount={50}
                visible={ this.props.sceneNavigator.viroAppProps.frontGardenMarkerVisible }
                radius={1.0}
                position={ this.state.position3 }
                onClick={() => this._showVideoPreview('3_ZofiaRysHanuszkiewicz17.mp4', '2D', this.state.position3 )}
                materials={["material3"]} />
            
            <ViroSphere heightSegmentCount={50}
                animation={{name:"scaleCar", run:this.state.animateCar,}}
                widthSegmentCount={50}
                visible={ this.props.sceneNavigator.viroAppProps.frontGardenMarkerVisible }
                radius={1.0}
                position={ this.state.position4 }
                onClick={() => this._showVideoPreview('04_Dach.mp4', '360', this.state.position4 )}
                materials={["material4"]} />

            <ViroSphere heightSegmentCount={50}
                animation={{name:"scaleCar", run:this.state.animateCar,}}
                widthSegmentCount={50}
                visible={ this.props.sceneNavigator.viroAppProps.frontGardenMarkerVisible }
                radius={1.0}
                position={ this.state.position5 }
                onClick={() => this._showVideoPreview('5_Lagertor_RB_Innen.mp4', '360', this.state.position5 )}
                materials={["material5"]} />

            <ViroSphere heightSegmentCount={50}
                animation={{name:"scaleCar", run:this.state.animateCar,}}
                widthSegmentCount={50}
                visible={ this.props.sceneNavigator.viroAppProps.frontGardenMarkerVisible }
                radius={1.0}
                position={ this.state.position6 }
                onClick={() => this._showVideoPreview('06_Lagerstrasse.mp4', '360', this.state.position6 )}
                materials={["material6"]} />
            
            <ViroSphere heightSegmentCount={50}
                animation={{name:"scaleCar", run:this.state.animateCar,}}
                widthSegmentCount={50}
                visible={ this.props.sceneNavigator.viroAppProps.frontGardenMarkerVisible }
                radius={1.0}
                position={ this.state.position7 }
                onClick={() => this._showVideoPreview('7_Sabine Arend.mp4', '360', this.state.position7 )}
                materials={["material7"]} />
            
            <ViroSphere heightSegmentCount={50}
                animation={{name:"scaleCar", run:this.state.animateCar,}}
                widthSegmentCount={50}
                visible={ this.props.sceneNavigator.viroAppProps.frontGardenMarkerVisible }
                radius={1.0}
                position={ this.state.position8 }
                onClick={() => this._showVideoPreview('8_Ravensbrueck_Kantor.mp4', '360', this.state.position8 )}
                materials={["material8"]} />
            
            <ViroSphere heightSegmentCount={50}
                animation={{name:"scaleCar", run:this.state.animateCar,}}
                widthSegmentCount={50}
                visible={ this.props.sceneNavigator.viroAppProps.frontGardenMarkerVisible }
                radius={1.0}
                position={ this.state.position9 }
                onClick={() => this._showVideoPreview('9_EdithSparmann_01.mp4', '2D', this.state.position9 )}
                materials={["material9"]} />

            <ViroSphere heightSegmentCount={50}
                animation={{name:"scaleCar", run:this.state.animateCar,}}
                widthSegmentCount={50}
                visible={ this.props.sceneNavigator.viroAppProps.frontGardenMarkerVisible }
                radius={1.0}
                position={ this.state.position10 }
                onClick={() => this._showVideoPreview('10_Barracken.mp4', '360', this.state.position10 )}
                materials={["material10"]} />

            <ViroSphere heightSegmentCount={50}
                animation={{name:"scaleCar", run:this.state.animateCar,}}
                widthSegmentCount={50}
                visible={ this.props.sceneNavigator.viroAppProps.frontGardenMarkerVisible }
                radius={1.0}
                position={ this.state.position11 }
                onClick={() => this._showVideoPreview('11_WaltraudBlass13.mp4', '2D', this.state.position11 )}
                materials={["material11"]} />
            
            <ViroSphere heightSegmentCount={50}
                animation={{name:"scaleCar", run:this.state.animateCar,}}
                widthSegmentCount={50}
                visible={ this.props.sceneNavigator.viroAppProps.frontGardenMarkerVisible }
                radius={1.0}
                position={ this.state.position12 }
                onClick={() => this._showVideoPreview('12_FriedrichKlingenberg04.mp4', '2D', this.state.position12 )}
                materials={["material12"]} />

            <ViroSphere heightSegmentCount={50}
                animation={{name:"scaleCar", run:this.state.animateCar,}}
                widthSegmentCount={50}
                visible={ this.props.sceneNavigator.viroAppProps.frontGardenMarkerVisible }
                radius={1.0}
                position={ this.state.position13 }
                onClick={() => this._showVideoPreview('13_Kranzniederlegung.mp4', '360', this.state.position13 )}
                materials={["material13"]} />

            <ViroSphere heightSegmentCount={50}
                animation={{name:"scaleCar", run:this.state.animateCar,}}
                widthSegmentCount={50}
                visible={ this.props.sceneNavigator.viroAppProps.frontGardenMarkerVisible }
                radius={1.0}
                position={ this.state.position14 }
                onClick={() => this._showVideoPreview('14_Luis.mp4', '360', this.state.position14 )}
                materials={["material14"]} />

            <ViroSphere heightSegmentCount={50}
                animation={{name:"scaleCar", run:this.state.animateCar,}}
                widthSegmentCount={50}
                visible={ this.props.sceneNavigator.viroAppProps.frontGardenMarkerVisible }
                radius={1.0}
                position={ this.state.position15 }
                onClick={() => this._showVideoPreview('15_Diana.mp4', '360', this.state.position15 )}
                materials={["material15"]} />

            <ViroSphere heightSegmentCount={50}
                animation={{name:"scaleCar", run:this.state.animateCar,}}
                widthSegmentCount={50}
                visible={ this.props.sceneNavigator.viroAppProps.frontGardenMarkerVisible }
                radius={1.0}
                position={ this.state.position16 }
                onClick={() => this._showVideoPreview('16_Eeris.mp4', '360', this.state.position16 )}
                materials={["material16"]} />

            <ViroSphere heightSegmentCount={50}
                animation={{name:"scaleCar", run:this.state.animateCar,}}
                widthSegmentCount={50}
                visible={ this.props.sceneNavigator.viroAppProps.frontGardenMarkerVisible }
                radius={1.0}
                position={ this.state.position17 }
                onClick={() => this._showVideoPreview('17_Emmy.mp4', '360', this.state.position17 )}
                materials={["material17"]} />

            <ViroSphere heightSegmentCount={50}
                animation={{name:"scaleCar", run:this.state.animateCar,}}
                widthSegmentCount={50}
                visible={ this.props.sceneNavigator.viroAppProps.frontGardenMarkerVisible }
                radius={1.0}
                position={ this.state.position18 }
                onClick={() => this._showVideoPreview('18_Dancers.mp4', '360', this.state.position18 )}
                materials={["material18"]} />

            <ViroSphere heightSegmentCount={50}
                animation={{name:"scaleCar", run:this.state.animateCar,}}
                widthSegmentCount={50}
                visible={ this.props.sceneNavigator.viroAppProps.frontGardenMarkerVisible }
                radius={1.0}
                position={ this.state.position19 }
                onClick={() => this._showVideoPreview('19_Dan_Wolf.mp4', '360', this.state.position19 )}
                materials={["material19"]} />

            <ViroSphere heightSegmentCount={50}
                animation={{name:"scaleCar", run:this.state.animateCar,}}
                widthSegmentCount={50}
                visible={ this.props.sceneNavigator.viroAppProps.frontGardenMarkerVisible }
                radius={1.0}
                position={ this.state.position20 }
                onClick={() => this._showVideoPreview('20_Bahnhof_Ambisonic.mp4', '360', this.state.position20 )}
                materials={["material20"]} />
            
            <ViroSphere heightSegmentCount={50}
                animation={{name:"scaleCar", run:this.state.animateCar,}}
                widthSegmentCount={50}
                visible={ this.props.sceneNavigator.viroAppProps.frontGardenMarkerVisible }
                radius={1.0}
                position={ this.state.position23 }
                onClick={() => this._showVideoPreview('23_RayEsther.mp4', '360', this.state.position23 )}
                materials={["material23"]} />

            <ViroSphere heightSegmentCount={50}
                animation={{name:"scaleCar", run:this.state.animateCar,}}
                widthSegmentCount={50}
                visible={ this.props.sceneNavigator.viroAppProps.frontGardenMarkerVisible }
                radius={1.0}
                position={ this.state.position24 }
                onClick={() => this._showVideoPreview('24_Textilfabrik_Ambisonic.mp4', '360', this.state.position24 )}
                materials={["material24"]} />

            {/* <ViroSphere heightSegmentCount={50}
                animation={{name:"scaleCar", run:this.state.animateCar,}}
                widthSegmentCount={50}
                visible={ this.props.sceneNavigator.viroAppProps.frontGardenMarkerVisible }
                radius={1.0}
                position={ this.state.position125 }
                onClick={() => this._showVideoPreview('20_Bahnhof_Ambisonic.mp4', '360', this.state.position125 )}
                materials={["material1"]} />

            <ViroSphere heightSegmentCount={50}
                animation={{name:"scaleCar", run:this.state.animateCar,}}
                widthSegmentCount={50}
                visible={ this.props.sceneNavigator.viroAppProps.frontGardenMarkerVisible }
                radius={1.0}
                position={ this.state.position126 }
                onClick={() => this._showVideoPreview('20_Bahnhof_Ambisonic.mp4', '360', this.state.position126 )}
                materials={["material2"]} />
            
            <ViroSphere heightSegmentCount={50}
                animation={{name:"scaleCar", run:this.state.animateCar,}}
                widthSegmentCount={50}
                visible={ this.props.sceneNavigator.viroAppProps.frontGardenMarkerVisible }
                radius={1.0}
                position={ this.state.position127 }
                onClick={() => this._showVideoPreview('3_ZofiaRysHanuszkiewicz17.mp4', '2D', this.state.position127 )}
                materials={["material3"]} /> */}
                

            <ViroNode visible={this.props.sceneNavigator.viroAppProps.lagertorVideoPreviewVisible}>
                { this.props.sceneNavigator.viroAppProps.playing360VideoSource && <Viro360Video
                    rotation={[0, 45, 0]}
                    onFinish={this._lagertorVideoFinished}
                    source={this.props.sceneNavigator.viroAppProps.playing360VideoSource}
                    loop={false}
                    paused={this.props.sceneNavigator.viroAppProps.objectIsPaused}
                    volume={1.0} /> }
            </ViroNode>
            
            {/* <ViroVideo
                position={ this.state.videoPosition }
                scale={[4, 4, 0]}
                visible={this.props.sceneNavigator.viroAppProps.twoDVideoPreviewVisible}
                onFinish={this._lagertorVideoFinished}
                source={this.props.sceneNavigator.viroAppProps.playing2DVideoSource}
                loop={false}
                paused={this.props.sceneNavigator.viroAppProps.twoDObjectIsPaused}
                volume={1.0} /> */}
            
            
        </ViroARScene>
        );
    }

	
});

module.exports = MainSceneAR;
