/**
 * Sample React Native App
 * https://github.com/facebook/react-native
 *
 * @format
 * @flow strict-local
 */

import React, { useState } from 'react';
import type {Node} from 'react';
import {
	SafeAreaView,
	ScrollView,
	StatusBar,
	StyleSheet,
	Text,
	Alert,
	useColorScheme,
	Button,
	View,
	Modal,
	Pressable
} from 'react-native';
import MapView, { PROVIDER_GOOGLE } from 'react-native-maps';
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
	ViroARPlaneSelector
  } from '@viro-community/react-viro';

import {
	Colors,
	DebugInstructions,
	Header,
	LearnMoreLinks,
	ReloadInstructions,
} from 'react-native/Libraries/NewAppScreen';


ViroARTrackingTargets.createTargets({
	markerVidnessSample : {
		source : require('./media/markers/birds.jpg'),
		orientation : "Up",
		physicalWidth : 0.2
	},
	
});

ViroAnimations.registerAnimations({
	scaleUp:{
		properties:{scaleX:.5, scaleY:.5, scaleZ:.5,},
		duration: 500, easing: "bounce"
	},
});

const MainSceneAR = () => {
	const [pauseUpdates, setPauseUpdates] = useState(false);

	const [playAnim, setPlayAnim] = useState(false);
	
	const [sampleVisible, setSampleVisible] = useState(false);

	function _onAnchorFound() {
		console.log('Anchor was found')
		setSampleVisible(true);
		setPauseUpdates(true);
		setPlayAnim(true);
	}

	return (
		<ViroARScene>
			<ViroAmbientLight color="#ffffff" intensity={200}/>
			
				<ViroARImageMarker target={"markerVidnessSample"} onAnchorFound={_onAnchorFound} pauseUpdates={pauseUpdates}>
					<ViroNode visible={sampleVisible} scale={[0, 0, 0]} rotation={[-90, 0, 0]} animation={{name:"scaleUp", run:playAnim,}}>
						<ViroPortalScene passable={true} onDrag={()=>{}}>
							<ViroPortal position={[0, 0, -1]}>
								<Viro3DObject source={require('./media/frame.vrx')}
									type="VRX"/>
							</ViroPortal>
							<Viro360Video source={require("./media/5_Lagertor_RB_Innen.mp4")} loop={true} />
						</ViroPortalScene>
					</ViroNode>
					{/* <Viro3DObject scale={[0, 0, 0]} rotation={[-90, 0, 0]} source={require('./media/portal_ship.vrx')} 
						animation={{name:"scaleUp", run:playAnim,}}
						resources={[require('./media/portal_ship_diffuse.png'),
									require('./media/portal_ship_normal.png'),
									require('./media/portal_ship_specular.png')]}
						type="VRX"/> */}
						{/*  */}
					
					{/* <ViroNode visible={sampleVisible} position={[0, 0, 0]} scale={[0,0,0]} rotation={[-90, 0, 0]} dragType="FixedToWorld" onDrag={()=>{}}
						animation={{name:"scaleUp", run:playAnim,}} >
						<ViroPortalScene passable={true} onDrag={()=>{}}>
							<ViroPortal position={[0, 0, -1]} scale={[.2, .2, .2]}>
								<Viro3DObject source={require('./media/portal_ship.vrx')}
									resources={[require('./media/portal_ship_diffuse.png'),
												require('./media/portal_ship_normal.png'),
												require('./media/portal_ship_specular.png')]}
									type="VRX"/>
							</ViroPortal>
							<Viro360Video source={require("./media/360_surf.mp4")} loop={true} />
						</ViroPortalScene>
					</ViroNode> */}
				
				</ViroARImageMarker>
			
		</ViroARScene>
	)
}

const App: () => Node = () => {
	const [modalVisible, setModalVisible] = useState(false);

	return (
		<View style={{ flex: 1 }}>
			<Modal
				animationType="slide"
				transparent={true}
				visible={modalVisible}
				onRequestClose={() => { setModalVisible(!modalVisible); }}>
				<View style={styles.centeredView}>
					<MapView
						style={styles.mapOverlay}
						provider={PROVIDER_GOOGLE} // remove if not using Google Maps
						region={{
							latitude: 37.78825,
							longitude: -122.4324,
							latitudeDelta: 0.015,
							longitudeDelta: 0.0121,
						}}
						>
					</MapView>
				</View>
			</Modal>
			<ViroARSceneNavigator
				autofocus={true}
				initialScene={{
					scene: MainSceneAR,
				}}
				style={styles.f1}
				/>
			<Button style={styles.floatingButton} title="Press me" onPress={() => setModalVisible(true) } />
		</View>
		
	);
};



const styles = StyleSheet.create({
	centeredView: {
		flex: 1,
		justifyContent: 'center',
		alignItems: 'center',
		marginTop: 22,
	},
	modalView: {
		margin: 20,
		backgroundColor: 'white',
		borderRadius: 20,
		padding: 35,
		alignItems: 'center',
		shadowColor: '#000',
		shadowOffset: {
			width: 0,
			height: 2,
		},
		shadowOpacity: 0.25,
		shadowRadius: 4,
		elevation: 5,
	},
	floatingButton: {
		position: 'absolute',
		bottom: 60,
		right: 60,
		width: 100
	},	
	mapOverlay: {
		width: '90%',
		height: '90%',
		borderRadius: 20,
	},
	f1: { flex: 1, backgroundColor: 'transparent' },
});

export default App;
