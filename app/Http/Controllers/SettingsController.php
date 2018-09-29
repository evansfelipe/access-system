<?php namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\{ Gate, Activity, Subactivity, VehicleType };

class SettingsController extends Controller
{
    /**
     * Adds a new gate to the system.
     */
    public function newGate(Request $request)
    {
        Validator::make($request->all(), Gate::getValidationRules())->validate();
        $gate = new Gate(['name' => $request->name, 'enabled' => $request->enabled ?? false]);
        $gate->save();
        return response(json_encode($gate->toListArray()), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Updates an existing gate from the system.
     */
    public function updateGate(Request $request, Gate $gate)
    {
        // Updates the object if at least one value is different to the current ones.
        if(($request->name !== $gate->name) || ($request->enabled !== $gate->enabled)) {
            $validation_rules = Gate::getValidationRules();
            // If the name hadn't change, removes the unique rule so the validation won't fail.
            if($request->name === $gate->name && ($key = array_search('unique:gates,name', $validation_rules['name'])) !== false) {
                unset($validation_rules['name'][$key]);
            }
            Validator::make($request->all(), $validation_rules)->validate();
            $gate->name     = $request->name;
            $gate->enabled  = $request->enabled ?? false;
            $gate->save();
        }
        return response(json_encode($gate->toListArray()), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Adds a new activity to the system.
     */
    public function newActivity(Request $request)
    {
        Validator::make($request->all(), Activity::getValidationRules())->validate();
        $activity = new Activity(['name' => $request->name]);
        $activity->save();
        return response(json_encode($activity->toListArray()), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Updates an existing activity from the system.
     */
    public function updateActivity(Request $request, Activity $activity)
    {
        // Updates the object if at least one value is different to the current ones.
        if($request->name !== $activity->name) {
            Validator::make($request->all(), Activity::getValidationRules())->validate();
            $activity->name = $request->name;
            $activity->save();
        }
        return response(json_encode($activity->toListArray()), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Adds a new subactivity associated with an existing activity to the system.
     */
    public function newSubactivity(Request $request)
    {
        Validator::make($request->all(), Subactivity::getValidationRules())->validate();
        $subactivity = new Subactivity(['name' => $request->name, 'activity_id' => $request->activity_id]);
        $subactivity->save();
        return response(json_encode($subactivity->toListArray()), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Updates an existing subactivity from the system. It's not possible to change the associated activity.
     */
    public function updateSubactivity(Request $request, Subactivity $subactivity)
    {
        // Updates the object if at least one value is different to the current ones.
        if($request->name !== $subactivity->name) {
            Validator::make($request->all(), Subactivity::getValidationRules())->validate();
            $subactivity->name = $request->name;
            $subactivity->save();
        }
        return response(json_encode($subactivity->toListArray()), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Adds a new vehicle type to the system.
     */
    public function newVehicleType(Request $request)
    {
        Validator::make($request->all(), VehicleType::getValidationRules())->validate();
        $vehicle_type = new VehicleType(['type' => $request->type, 'allows_container' => $request->allows_container]);
        $vehicle_type->save();
        return response(json_encode($vehicle_type->toListArray()), 200)->header('Content-Type', 'application/json');
    }

    /**
     * Updates an existing vehicle type from the system. It's not possible to change de "allows_container" attribute
     * from true to false if there is at least one vehicle of this type associated with at least one container.
     */
    public function updateVehicleType(Request $request, VehicleType $vehicle_type)
    {
        // Updates the object if at least one value is different to the current ones.
        if(($request->type !== $vehicle_type->type) || ($request->allows_container !== $vehicle_type->allows_container)) {
            $validation_rules = VehicleType::getValidationRules();
            // If currently the type allows containers then adds a validation rule for prevent changing the "allows_container"
            // attribute from true to false if there are containers associated with at least one vehicle of this type.
            if($vehicle_type->allows_container == true) {
                $assigned = false;

                foreach($vehicle_type->vehicles as $vehicle) {
                    if($vehicle->containers()->exists()) {
                        $assigned = true;
                        break;
                    }
                }

                $validation_rules['allows_container'] = array_merge($validation_rules['allows_container'], [
                    function($attribute, $value, $fail) use($vehicle_type, $assigned) {
                        if($vehicle_type->allows_container != $value && $assigned)
                            return $fail('Hay vehículos de este tipo con contenedores asignados.');
                    }
                ]);
            }
            // If the name hadn't change, removes the unique rule so the validation won't fail.
            if($request->type === $vehicle_type->type && ($key = array_search('unique:vehicle_types', $validation_rules['type'])) !== false) {
                unset($validation_rules['type'][$key]);
            }
            // Validates the request and if it pass, updates the type.
            Validator::make($request->all(), $validation_rules)->validate();
            $vehicle_type->type = $request->type;
            $vehicle_type->allows_container = $request->allows_container;
            $vehicle_type->save();
        }
        return response(json_encode($vehicle_type->toListArray()), 200)->header('Content-Type', 'application/json');
    }
}