import React, { Component, useState } from 'react';

var { Dimensions } = require('react-native')

import { Spinner, 
    Picker, Item, NativeBaseProvider, Button, Icon, Ionicons, Modal, Select } from 'native-base';
import {
	AppRegistry,
    SafeAreaView,
    Animated,
    Image,
    TouchableHighlight,
	ScrollView,
	StatusBar,
	StyleSheet,
    Heading,
	Text,
	Alert,
	useColorScheme,
    View,
    Linking
} from 'react-native';
import MapView, { Callout, Marker, PROVIDER_GOOGLE } from 'react-native-maps';

import {
	ViroARSceneNavigator,
	ViroSceneNavigator,
	ViroNode,
	ViroARScene,
	ViroAmbientLight,
	Viro360Video,
	ViroAnimations,
	Viro360Image,
	ViroUtils,
	ViroPortal,
	ViroPortalScene,
	Viro3DObject,
	ViroARTrackingTargets,
	ViroARImageMarker,
    ViroMaterials,
	ViroARPlaneSelector,
    ViroSphere
} from '@viro-community/react-viro';
import {Svg, Image as ImageSvg} from 'react-native-svg';

const geolib = require('geolib');
import styles from './_common/styles';
import Geolocation from '@react-native-community/geolocation';

import CompassHeading from 'react-native-compass-heading';

var InitialARScene = require('./MainARScene.js');

const window = Dimensions.get('window');

const ENABLE_DEBUG = false;
const VISIBLE_LOCATION_DISTANCE = 20;
const INITIAL_OFFSET_LEFT = -200;
const SERVER_URL = 'http://localhost:8082/aa';



export default class Explore extends Component {

    constructor(props) {
        super(props);
        
        this._externalShowVideoPreview = this._externalShowVideoPreview.bind(this);
        this._resetARScene = this._resetARScene.bind(this);
        this._arSceneWasReset = this._arSceneWasReset.bind(this);

        this.state = {
            heading: 0,
            myLocation: { latitude: 0, longitude: 0 },
            currentAnimatedValue: 0,
            screenPositionLeft: new Animated.Value(0),
            archiveLocations: null,
            selectedCategory: '',
            allVisibleLocations: [],
            visibleLocations: [],
            
            currentChannel: 0,
            channels: null,
            visibleChannels: null,
            showChannelDialog: false,
            
            videoPlayerPath: '',
            categorySelect: '1',
            newVideoTitle: '',

            isRecording: false,
            capturedVideoData: null,
            uploadOverlay: 0,
            uploadProgress: 0,
            uploadDone: 0,
            showRecordingControls: false,
            requestFromUserAccess: false,
            modalVisible: false,
            menuModalVisible: false,
            instructionsModalVisible: false,

            showCalloutKey: -1,
            objectIsPaused: true,
            currentLocationLatitude: null,
            currentLocationLongitude: null,
            twoDObjectIsPaused: true,
            mode: 'welcome',
            map: null,
            arScene: null,

            playing360VideoSource: null,
            playing2DVideoSource: null,
            lagertorVideoPreviewVisible: false,
            twoDVideoPreviewVisible: false,

            internalFrontGardenMarkerVisible: true,
            videoType: '360',

            internalPlayingVideo: {
                uri: 'https://tq-vidness.s3.eu-central-1.amazonaws.com/19_Dan_Wolf.mp4'
            },
            showInternalVideoPlayer: true,
            reopensToMap: false,
            categories: ['Alle Videos', 'Alle Videos', 'Interview', 'Perspektive', 'Zeitzeug:innen', 'Dokumentation'],
            loading: true,
        }
    }

    async componentDidMount() {
		// get all the locations for archives
		fetch('https://www.spurlab-vidness.de/vidnessapi/ws2/locations', {
			'method': 'POST'
		})
			.then((response) => response.json())
			.then((responseData) => {
                let responseLocations = [];
                if (responseData.Locations) {
                    responseData.Locations.map(l => {
                        let tempLocation = {
                            Id: l.Id,
                            Title: l.Title,
                            SubTitle: l.SubTitle,
                            CategoryId: l.Category.Id,
                            Category: l.Category.Name,
                            Date: l.Date,
                            Latitude: l.Latitude,
                            Longitude: l.Longitude,
                            Preview: l.PreviewPath,
                            Video: l.VideoPath,
                            VideoType: l.VideoType
                        }
                        responseLocations.push(tempLocation);
                    })
                }
                
				this.setState({
                    allVisibleLocations: responseLocations,
                    visibleLocations: responseLocations
                });
			})
			.done();
	}

    onCategoryChange(value) {
        this.setState({
            categorySelect: value
        });
    }

    setObjectIsPaused(playState) {
        
        if (this.state.videoType == '360') {
            console.log('Play state of 360 changed to ', playState);
            this.setState({
                objectIsPaused: playState
            });
        } else {
            console.log('Play state of 2D changed to ', playState);
            this.setState({
                twoDObjectIsPaused: playState
            });
        }
        
    }

    showInstructions() {
        this.setState({
            menuModalVisible: false,
            instructionsModalVisible: true
        })
    }
 
    openLink(link) {
       Linking.openURL(link).catch(err => console.error("Couldn't load page", err));
    }

    switchMode(newMode) {
        console.log('Switch mode', newMode);
        this.setState({
            mode: newMode
        });

        if (newMode == 'map') {
            this.setState({
                modalVisible: true
            })
        }
    }

    centerMarker(latlng) {
        let region = {
            latitude: latlng.latitude,
            longitude: latlng.longitude,
            latitudeDelta: 0.0040,
            longitudeDelta: 0.0040
        }
        this.map.animateToRegion(region, 300);
    }
    
    _externalShowVideoPreview(videoName, videoType, reopensToMap = false) {
        console.log('Will show video with type', videoName, videoType);
        let completeVideoPath = '';
        if (videoName.indexOf('https://') > -1) {
            completeVideoPath = videoName;
        } else {
            completeVideoPath = 'https://tq-vidness.s3.eu-central-1.amazonaws.com/' + videoName;
        }
        this.setState({
            videoType: videoType,
            reopensToMap: reopensToMap
        })
        if (videoType == '360') {
            this.setState({
                objectIsPaused: false,
                playing360VideoSource: {
                    uri: completeVideoPath
                },
                lagertorVideoPreviewVisible: true,
                frontGardenMarkerVisible: false,
                twoDObjectIsPaused: true,
                twoDVideoPreviewVisible: false,
                modalVisible: false,
            });
        } else {
            this.setState({
                twoDObjectIsPaused: false,
                playing2DVideoSource: {
                    uri: completeVideoPath
                },
                twoDVideoPreviewVisible: true,
                objectIsPaused: true,
                lagertorVideoPreviewVisible: false,
                frontGardenMarkerVisible: false,
                modalVisible: false,
            });
        }
    }

    _resetARScene() {
        console.log('Will close video, external');
        // this.setState({
        //     twoDObjectIsPaused: true,
        //     playing2DVideoSource: {
        //         uri: 'https://tq-vidness.s3.eu-central-1.amazonaws.com/1_Matthias_Fuehrerhaus.mp4'
        //     },
        //     twoDVideoPreviewVisible: false,
        // })
        // this.state.arScene.resetARSession();
        this.setState({
            currentLocationLatitude: this.state.currentLocationLatitude,
            currentLocationLongitude: this.state.currentLocationLongitude,
            objectIsPaused: true,
            twoDObjectIsPaused: true,
            twoDVideoPreviewVisible: false,
            playing360VideoSource: null,
            playing2DVideoSource: {
                uri: 'https://tq-vidness.s3.eu-central-1.amazonaws.com/random.mp4'
            },
            lagertorVideoPreviewVisible: false,
            frontGardenMarkerVisible: true
        })
        setTimeout(() => {
            this._arScene.replace({ scene: InitialARScene, 
                passProps: { 
                    currentLocationLatitude: this.state.currentLocationLatitude,
                    currentLocationLongitude: this.state.currentLocationLongitude,
                    objectIsPaused: this.state.objectIsPaused,
                    twoDObjectIsPaused: this.state.twoDObjectIsPaused,
                    playing360VideoSource: this.state.playing360VideoSource,
                    playing2DVideoSource: this.state.playing2DVideoSource,
                    lagertorVideoPreviewVisible: this.state.lagertorVideoPreviewVisible,
                    frontGardenMarkerVisible: this.state.frontGardenMarkerVisible,
                    _externalShowVideoPreview: this._externalShowVideoPreview,
                    _resetARScene: this._resetARScene,
                    _arSceneWasReset: this._arSceneWasReset
                } 
            }) 
        }, 100);
        
        // this._arScene.resetARSession(true, true);
        // InitialARScene._resetARScene();
    }

    _arSceneWasReset() {
        console.log('Finished resetting AR scene');
        this.setState({
            requestResetARScene: false
        })
    }

    closeVideo() {
        if (this.state.reopensToMap) {
            this.setState({
                modalVisible: true,
                reopensToMap: false,
            })
        }
        this._resetARScene();
        // this.setState({
        //     objectIsPaused: true,
        //     lagertorVideoPreviewVisible: false,
        //     frontGardenMarkerVisible: true,
        // });
    }

    mapFilterUpdated(newValue) {
        // console.log('New value is', newValue);
        if (newValue == '') {
            this.setState({
                visibleLocations: this.state.allVisibleLocations
            });
        } else {
            let allLocations = this.state.allVisibleLocations;
            let tempVisibleLocations = allLocations.filter(v => v.CategoryId == parseInt(newValue));
            // console.log('New visible locations are', tempVisibleLocations);
            this.setState({
                visibleLocations: tempVisibleLocations
            });
        }

        this.setState({
            selectedCategory: newValue
        })
    } 

    _internalShowVideoPlayer(video, videoType) {
        if (videoType == '360') {
            console.log('Will attempt to video 360 video', video);
            // this.setState({
            //     showInternalVideoPlayer: true,
            //     internalPlayingVideo: {
            //         uri: 'https://tq-vidness.s3.eu-central-1.amazonaws.com/' + video
            //     }
            // })
        }
        
    }

    _getLocation(result) {
        // console.log('Complete location result', result);
        let location = result.nativeEvent.coordinate;
        // console.log('Current location', location);
        let targetLocation = { latitude: 41.025490497694946, longitude: 29.073197446134227 };
        let distanceFromTarget = geolib.getDistance({ latitude: location.latitude, longitude: location.longitude }, targetLocation);
        // console.log('Distance from center', location.latitude, location.longitude, distanceFromTarget);
        this.setState({
            currentLocationLatitude: location.latitude,
            currentLocationLongitude: location.longitude
        });
        // console.log('Google heading', location);
    }
    
    _toggleModal(newState) {
        console.log('Modal visible?', newState);
        this.setState({
            modalVisible: newState
        })
    }

    render() {
        mapStyle = [{ "elementType": "geometry", "stylers": [{ "color": "#f5f5f5" }] }, { "elementType": "labels.icon", "stylers": [{ "visibility": "off" }] }, { "elementType": "labels.text.fill", "stylers": [{ "color": "#616161" }] }, { "elementType": "labels.text.stroke", "stylers": [{ "color": "#f5f5f5" }] }, { "featureType": "administrative", "elementType": "geometry.stroke", "stylers": [{ "color": "#434343" }] }, { "featureType": "administrative.land_parcel", "elementType": "geometry.stroke", "stylers": [{ "color": "#434343" }] }, { "featureType": "administrative.land_parcel", "elementType": "labels.text.fill", "stylers": [{ "color": "#bdbdbd" }] }, { "featureType": "poi", "elementType": "geometry", "stylers": [{ "color": "#eeeeee" }] }, { "featureType": "poi", "elementType": "labels.text.fill", "stylers": [{ "color": "#757575" }] }, { "featureType": "poi.park", "elementType": "geometry", "stylers": [{ "color": "#e5e5e5" }] }, { "featureType": "poi.park", "elementType": "geometry.fill", "stylers": [{ "color": "#434343" }] }, { "featureType": "poi.park", "elementType": "labels.text.fill", "stylers": [{ "color": "#9e9e9e" }] }, { "featureType": "road", "elementType": "geometry", "stylers": [{ "color": "#ffffff" }] }, { "featureType": "road.arterial", "elementType": "labels.text.fill", "stylers": [{ "color": "#757575" }] }, { "featureType": "road.highway", "elementType": "geometry", "stylers": [{ "color": "#dadada" }] }, { "featureType": "road.highway", "elementType": "geometry.stroke", "stylers": [{ "color": "#434343" }] }, { "featureType": "road.highway", "elementType": "labels.text.fill", "stylers": [{ "color": "#616161" }] }, { "featureType": "road.local", "elementType": "labels.text.fill", "stylers": [{ "color": "#9e9e9e" }] }, { "featureType": "transit.line", "elementType": "geometry", "stylers": [{ "color": "#e5e5e5" }] }, { "featureType": "transit.line", "elementType": "labels.text.fill", "stylers": [{ "color": "#434343" }] }, { "featureType": "transit.station", "elementType": "geometry", "stylers": [{ "color": "#eeeeee" }] }, { "featureType": "water", "elementType": "geometry", "stylers": [{ "color": "#c9c9c9" }] }, { "featureType": "water", "elementType": "labels.text.fill", "stylers": [{ "color": "#9e9e9e" }] }];

        return (
            <NativeBaseProvider>
                {this.state.mode == 'welcome' ? 
                <ScrollView>

                {/* <Modal isOpen={this.state.showInternalVideoPlayer} onClose={() => this.setState({ showInternalVideoPlayer: false })} size={"xl"}>
                    <Modal.Content maxWidth="100%" maxH="100%">
                    <Modal.CloseButton />
                    <Modal.Body style={{ paddingTop: 50, paddingBottom: 40 }}>
                        <Video360 source={ this.state.internalPlayingVideo } style={{ flex: 1}} />
                    </Modal.Body>
                    </Modal.Content>
                </Modal> */}
                <View style={{ backgroundColor: 'white', height: '100%', padding: 20, paddingTop: 80, maxWidth: 400 }}>
                    <Image source={require('../resources/SpurLabLogo.png')} style={{ width: 144, height: 30, marginBottom: 20 }} />
                    <Text style={{ ...styles.bodyText, fontWeight: '200', fontSize: 80, lineHeight: 80, letterSpacing: -1 }}>Vidness</Text>
                    <Text style={{ ...styles.bodyText, fontWeight: 'bold', marginBottom: 30 }}>Vidness nutzt Augmented Reality um Videoinhalte in deiner Nähe anzuzeigen. Diese Videos, viele davon in 360°, markieren verschiedene Orte rund um das ehem. KZ Ravensbrück. In der Zukunft sollen weitere Orte der Verfolgung in Brandenburg hinzukommen. Zeitdokumente, Perspektiven, Interviews und künstlerische Beiträge gehen verschiedenen Formen der Erinnerungspraxis nach und können per Smartphone und Tablet oder als VR Installation erkundet und abgelaufen werden.</Text>

                    <Text style={{ ...styles.bodyText }}>Im Explore-Modus können die Inhalte vor Ort angesehen werden.
                        Zur Zeit beschränkt sich der Einsatz vor Ort auf die Umgebung der Mahn- und Gedenkstätte Ravensbrück.</Text>
                    <Button onPress={() => this.switchMode('explore')} style={ styles.floatingButton }><Text style={ styles.largeButtonText }>EXPLORE</Text></Button>

                    <Text style={{ ...styles.bodyText, marginTop: 30 }}>Alternativ können die Inhalte über eine Kartenansicht betrachtet werden.</Text>
                    <Button onPress={() => this.switchMode('map')} style={ styles.floatingButton }><Text style={ styles.largeButtonText }>MAP</Text></Button>

                    <Text style={{ ...styles.bodyText, marginTop: 30, fontSize: 15, textAlign: 'center' }}>Möchtest du mehr über VIDNESS erfahren?</Text>
                    <Button onPress={() => this.openLink('https://www.spurlab-vidness.de/')} style={ styles.floatingButton }>MEHR INFO</Button>
                
                </View>
                </ScrollView>
                
                :

                    <View style={{ flex: 1 }}>

                    <Modal isOpen={this.state.menuModalVisible} onClose={() => this.setState({ menuModalVisible: false })} size={"xl"}>
                        <Modal.Content maxWidth="400px">
                        <Modal.CloseButton />
                        <Modal.Body style={{ paddingTop: 50, paddingBottom: 40 }}>
                            <Button onPress={() => this.showInstructions()} style={ styles.floatingButton }>Über Vidness</Button>
                            <Button onPress={() => this.openLink('https://www.spurlab-vidness.de/')} style={ styles.floatingButton }>Website</Button>
                            <Button onPress={() => this.openLink('https://www.spurlab-vidness.de/privacy-policy/')} style={{ ...styles.floatingButton, marginBottom: 0 }}>Nutzungsbedingungen</Button>
                        </Modal.Body>
                        </Modal.Content>
                    </Modal>

                    <Modal isOpen={this.state.instructionsModalVisible} onClose={() => this.setState({ instructionsModalVisible: false })} size={"xl"}>
                        <Modal.Content maxWidth="400px">
                        <Modal.CloseButton />
                        <Modal.Body>
                            <View style={{ paddingTop: 40 }}>
                            <Text style={{ ...styles.bodyText, fontWeight: 'bold', marginBottom: 30 }}>Vidness nutzt Augmented Reality um Videoinhalte in deiner Nähe anzuzeigen. Diese Videos, viele davon in 360°, markieren verschiedene Orte rund um das ehem. KZ Ravensbrück. In der Zukunft sollen weitere Orte der Verfolgung in Brandenburg hinzukommen. Zeitdokumente, Perspektiven, Interviews und künstlerische Beiträge gehen verschiedenen Formen der Erinnerungspraxis nach und können per Smartphone und Tablet oder als VR Installation erkundet und abgelaufen werden.</Text>
                            <Text style={{ ...styles.bodyText }}>Im Explore-Modus können die Inhalte vor Ort angesehen werden.
                                Zur Zeit beschränkt sich der Einsatz vor Ort auf die Umgebung der Mahn- und Gedenkstätte Ravensbrück.</Text>
                                <Text style={{ ...styles.bodyText }}>Alternativ können die Inhalte über eine Kartenansicht betrachtet werden.</Text>
                            </View>
                        </Modal.Body>
                        </Modal.Content>
                    </Modal>


                    <ViroARSceneNavigator
                        ref={_arScene => { this._arScene = _arScene } }
                        autofocus={true}
                        initialScene={{
                            scene: InitialARScene
                        }}
                        viroAppProps={{ 
                            currentLocationLatitude: this.state.currentLocationLatitude,
                            currentLocationLongitude: this.state.currentLocationLongitude,
                            objectIsPaused: this.state.objectIsPaused,
                            twoDObjectIsPaused: this.state.twoDObjectIsPaused,
                            playing360VideoSource: this.state.playing360VideoSource,
                            playing2DVideoSource: this.state.playing2DVideoSource,
                            lagertorVideoPreviewVisible: this.state.lagertorVideoPreviewVisible,
                            frontGardenMarkerVisible: this.state.frontGardenMarkerVisible,
                            _externalShowVideoPreview: this._externalShowVideoPreview,
                            _resetARScene: this._resetARScene,
                            _arSceneWasReset: this._arSceneWasReset
                        }}// viroAppProps={ this.state.viroAppProps }
                        style={styles.f1}
                        />

                    <View style={{ position: 'absolute', left: 0, top: this.state.modalVisible ? 0 : '100%', right: 0, bottom: 0, width: '100%', height: '100%' }}>
                        <View style={{ marginTop: 70, right: 20, width: 120, position: 'absolute', zIndex: 2, backgroundColor: 'white', borderRadius: 3 }}>
                            <Select placeholder="Filter..." selectedValue={this.state.selectedCategory} onValueChange={itemValue => this.mapFilterUpdated(itemValue)}>
                                <Select.Item label="Alle Videos" value=""></Select.Item>
                                <Select.Item label="Interview" value="2"></Select.Item>
                                <Select.Item label="Perspektive" value="3"></Select.Item>
                                <Select.Item label="Zeitzeug:innen" value="4"></Select.Item>
                                <Select.Item label="Dokumentation" value="5"></Select.Item>
                            </Select>
                        </View>
                        <MapView
                            tracksViewChanges={ this.state.loading }
                            onUserLocationChange={ locationChangedResult => this._getLocation(locationChangedResult) }
                            ref={map => { this.map = map }}
                            onPress={() => {
                                if (this.state.showCalloutKey != -1) {
                                    this.setState({ showCalloutKey: -1 })
                                }
                            }}
                            style={{ ...styles.mapOverlay, width: this.state.modalVisible ? '100%' : 1, height: this.state.modalVisible ? '100%' : 1 }}
                            showsUserLocation={true}
                            provider={PROVIDER_GOOGLE}
                            initialRegion={{
                                latitude: 53.191786,
                                longitude: 13.163884,
                                latitudeDelta: 0.0040,
                                longitudeDelta: 0.0040,
                            }}
                            customMapStyle={mapStyle}
                            >
                            {this.state.visibleLocations && this.state.visibleLocations.map((marker, i) => {
                                let latlng = {
                                    latitude: parseFloat(marker.Latitude),
                                    longitude: parseFloat(marker.Longitude)
                                };

                                return (
                                    <Marker coordinate={latlng} key={i} onPress={() => this.centerMarker(latlng)}>
                                        <Image onLoad={() => this.setState({ loading: false })} source={require('../resources/Marker.png')} style={{ width: 42, height: 49, zIndex: 1 }} />
                                        <Callout onPress={() => this._externalShowVideoPreview(marker.Video, marker.VideoType, true) }> 
                                            <View style={ styles.calloutWrapper }>
                                                <Text style={ styles.calloutCategory }>{ marker.Category }</Text>
                                                {/* <Image source={{ uri: marker.Preview }} style={styles.calloutPreviewImage } /> */}
                                                <Svg width={180} height={120}>
                                                    <ImageSvg
                                                        width={'100%'} 
                                                        height={'100%'}
                                                        preserveAspectRatio="xMidYMid slice"
                                                        href={{ uri: marker.Preview }}
                                                    />
                                                </Svg>
                                                <Text style={{ ...styles.calloutTitle, marginTop: 10 }}>{ marker.Title }</Text>
                                                <Text style={ styles.calloutDate }>{ marker.Date }</Text>
                                                <Text style={ styles.calloutSubtitle }>{ marker.SubTitle }</Text>
                                            </View>
                                        </Callout>
                                    </Marker>
                                )
                            })}
                        </MapView>
                    </View>

                    { this.state.modalVisible ? <TouchableHighlight onPress={() => this._toggleModal(false) } style={styles.exploreOverlayButton}>
                        <Image source={require('../resources/ExploreOverlay.png')} style={styles.exploreOverlayButtonImage} />
                    </TouchableHighlight> : <TouchableHighlight onPress={() => this._toggleModal(true) } style={styles.mapOverlayButton}>
                        <Image source={require('../resources/MapOverlay.png')} style={styles.mapOverlayButtonImage} />
                    </TouchableHighlight> }

                    <TouchableHighlight onPress={() => this.setState({ menuModalVisible: true }) } style={styles.menuButton}>
                        <Image source={require('../resources/IconMenu.png')} style={styles.menuButtonImage} />
                    </TouchableHighlight>
                    {/* <TouchableHighlight onPress={() => this.setState({ modalVisible: true }) } style={styles.mapButton}>
                        <Image source={require('../resources/IconMarker.png')} style={styles.mapButtonImage} />
                    </TouchableHighlight> */}

                    { (this.state.lagertorVideoPreviewVisible || this.state.twoDVideoPreviewVisible) && <TouchableHighlight onPress={() => this.closeVideo() } style={styles.closeButton}>
                        <Image source={require('../resources/IconClose.png')} style={styles.closeButtonImage} />
                    </TouchableHighlight> }
                    { this.state.lagertorVideoPreviewVisible &&
                        <View>{ this.state.objectIsPaused ?

                            <TouchableHighlight onPress={() => this.setObjectIsPaused(false) } style={styles.controlButton}>
                                <Image source={require('../resources/IconPlay.png')} style={styles.controlButtonImage} />
                            </TouchableHighlight> :

                            <TouchableHighlight onPress={() => this.setObjectIsPaused(true) } style={styles.controlButton}>
                                <Image source={require('../resources/IconPause.png')} style={styles.controlButtonImage} />
                            </TouchableHighlight> 
                        }</View>
                    }

                    { this.state.twoDVideoPreviewVisible && 
                        <View>{ this.state.twoDObjectIsPaused ?

                            <TouchableHighlight onPress={() => this.setObjectIsPaused(false) } style={styles.controlButton}>
                                <Image source={require('../resources/IconPlay.png')} style={styles.controlButtonImage} />
                            </TouchableHighlight> :

                            <TouchableHighlight onPress={() => this.setObjectIsPaused(true) } style={styles.controlButton}>
                                <Image source={require('../resources/IconPause.png')} style={styles.controlButtonImage} />
                            </TouchableHighlight> 
                        }</View> }

                    </View>
                
                }
            </NativeBaseProvider>
        )
    }
}


AppRegistry.registerComponent('Explore', () => Explore);