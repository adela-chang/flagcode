//
//  ViewController.h
//  CaptureTheFlag
//
//  Created by Adela Chang on 6/15/13.
//  Copyright (c) 2013 Squirrel, Inc. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ViewController : UIViewController {
    IBOutlet CLLocationManager *locationManager;
    NSTimer *aTimer;
    NSMutableDictionary *allLocations;
}
@property (weak, nonatomic) IBOutlet MKMapView *mapView;

@end
