//
//  ViewController.m
//  CaptureTheFlag
//
//  Created by Adela Chang on 6/15/13.
//  Copyright (c) 2013 Squirrel, Inc. All rights reserved.
//

#import "ViewController.h"
#import "AFHTTPClient.h"
#import "AFNetworking.h"

#define SYSTEM_VERSION_EQUAL_TO(v)                  ([[[UIDevice currentDevice] systemVersion] compare:v options:NSNumericSearch] == NSOrderedSame)
#define SYSTEM_VERSION_GREATER_THAN(v)              ([[[UIDevice currentDevice] systemVersion] compare:v options:NSNumericSearch] == NSOrderedDescending)
#define SYSTEM_VERSION_GREATER_THAN_OR_EQUAL_TO(v)  ([[[UIDevice currentDevice] systemVersion] compare:v options:NSNumericSearch] != NSOrderedAscending)
#define SYSTEM_VERSION_LESS_THAN(v)                 ([[[UIDevice currentDevice] systemVersion] compare:v options:NSNumericSearch] == NSOrderedAscending)
#define SYSTEM_VERSION_LESS_THAN_OR_EQUAL_TO(v)     ([[[UIDevice currentDevice] systemVersion] compare:v options:NSNumericSearch] != NSOrderedDescending)

@interface ViewController ()

@end

@implementation ViewController

-(void)timerFired:(NSTimer *) theTimer
{    
    locationManager = [[CLLocationManager alloc] init];
    locationManager.distanceFilter = kCLDistanceFilterNone; // whenever we move
    locationManager.desiredAccuracy = kCLLocationAccuracyHundredMeters; // 100 m
    [locationManager startUpdatingLocation];
    
    NSString *udid;
    
    if (SYSTEM_VERSION_GREATER_THAN_OR_EQUAL_TO(@"6.0"))
        udid = [UIDevice currentDevice].identifierForVendor.UUIDString;
    else
        udid = [UIDevice currentDevice].uniqueIdentifier;
    //NSLog(@"%@", udid);
    NSNumber *latitude = [NSNumber numberWithDouble:locationManager.location.coordinate.latitude];
    NSNumber *longitude = [NSNumber numberWithDouble:locationManager.location.coordinate.longitude];
    
    NSURL *url = [NSURL URLWithString:@"http://ichinosekai.net/"];
    AFHTTPClient *httpClient = [[AFHTTPClient alloc] initWithBaseURL:url];
    [httpClient defaultValueForHeader:@"Accept"];
    
    NSDictionary *params = [NSDictionary dictionaryWithObjectsAndKeys:
                            udid, @"id",
                            latitude, @"mylat",
                            longitude, @"mylong",
                            nil];

    
    [httpClient postPath:@"/test2.php" parameters:params success:^(AFHTTPRequestOperation *operation, id responseObject) {
        NSError *err = nil;
        NSDictionary *jsonDict = [NSJSONSerialization JSONObjectWithData:responseObject options:0 error:&err];
        if (!jsonDict) {
            NSLog(@"Error parsing JSON: %@", err);
        } else {
            NSEnumerator *enumerator = [jsonDict keyEnumerator];
            id key;
            while ((key = [enumerator nextObject])) {
                if ([key isEqualToString:udid]) {
                } else {
                    NSArray *tmp = [jsonDict objectForKey:key];
                    NSString *tmplat = [tmp objectAtIndex:0];
                    NSString *tmplong = [tmp objectAtIndex:1];
                    
                    CLLocationCoordinate2D coordinate;
                    coordinate.latitude = [tmplat doubleValue];
                    coordinate.longitude = [tmplong doubleValue];
                    
                    
                    id value = [allLocations objectForKey:key];
                    if (value == nil) {
                        MKPointAnnotation *point = [[MKPointAnnotation alloc] init];
                        
                        [point setCoordinate:coordinate];
                        [point setTitle:key];
                        
                        [allLocations setObject:point forKey:key];
                        [_mapView addAnnotation:point];
                    } else {
                        [((MKPointAnnotation *) value) setCoordinate:coordinate];
                    }
                }
            }
        }
        
        NSLog(@"Request Successful, response '%@'", jsonDict);
    } failure:^(AFHTTPRequestOperation *operation, NSError *error) {
        NSLog(@"[HTTPClient Error]: %@", error.localizedDescription);
    }];

/*    AFHTTPRequestOperation *operation = [AFJSONRequestOperation JSONRequestOperationWithRequest:request success:^(NSURLRequest *request, NSURLResponse *response, id responseObject) {
    } failure:^(NSURLRequest *request, NSURLResponse *response, NSError *error, id JSON) {
        NSLog(@"[HTTPClient Error]: %@", error.localizedDescription);
    }];
    [operation start];*/
}

- (void)viewDidLoad
{
    [super viewDidLoad];
    
    aTimer = [NSTimer scheduledTimerWithTimeInterval:1.0
                                              target:self
                                            selector:@selector(timerFired:)
                                            userInfo:nil
                                             repeats:YES];

}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

@end