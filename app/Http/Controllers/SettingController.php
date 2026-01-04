<?php

namespace App\Http\Controllers;

use App\Models\CustomerType;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:general-setting-maintain', ['only' => ['generalSetting', 'generalSettingUpdate']]);
        $this->middleware('permission:customer-type-list', ['only' => ['customerType']]);
        $this->middleware('permission:customer-type-create|customer-type-edit', ['only' => ['customerTypeStore']]);
        $this->middleware('permission:customer-type-create', ['only' => ['customerTypeStore']]);
        $this->middleware('permission:customer-type-edit', ['only' => ['edit']]);
        $this->middleware('permission:customer-type-delete', ['only' => ['customerTypeDelete']]);
    }
    public function generalSetting()
    {
        $pageTitle = 'General Settings';
        $generals = GeneralSetting::find(1);
        return view('setting.general', compact('pageTitle', 'generals'));
    }

    public function generalSettingUpdate(Request $request)
    {
        $generalSetting = gs();
        $generalSetting->company_name = $request->company_name;
        $generalSetting->owner_name = $request->owner_name;
        $generalSetting->trn_number = $request->trn_number;
        $generalSetting->primary_contact_number =  $request->primary_contact_number;
        $generalSetting->alternate_contact_number =  $request->alternate_contact_number;
        $generalSetting->primary_email_address =  $request->primary_email_address;
        $generalSetting->alternate_email_address =  $request->alternate_email_address;
        $generalSetting->website_url = $request->website_url;
        $generalSetting->pagination = $request->pagination;
        // $generalSetting->bs_module =  $request->bs_module;
        // $generalSetting->subcategory_module =  $request->subcategory_module;
        // $generalSetting->brand_module =  $request->brand_module;
        // $generalSetting->barcode =  $request->barcode;
        $generalSetting->address =  $request->address;

        if ($request->hasFile('logo')) {
            $uploadedLogo = uploadImage($request->file('logo'), 'logo', 600,  $generalSetting->logo);
            $generalSetting->logo = $uploadedLogo;
        }

        if ($request->hasFile('favicon')) {
            $uploadedFavicon = uploadImage($request->file('favicon'), 'favicon', 100,  $generalSetting->favicon);
            $generalSetting->favicon = $uploadedFavicon;
        }

        if ($request->hasFile('user_image')) {
            $uploadedUserImage = uploadImage($request->file('user_image'), 'profile', 100,  $generalSetting->user_image);
            $generalSetting->user_image = $uploadedUserImage;
        }

        $generalSetting->save();

        $notify[] = ['success', 'Settings updated successfully.'];
        return back()->withNotify($notify);
    }

    public function customerType()
    {
        $pageTitle = 'Customer Type';
        $types = CustomerType::latest()->notDeleted()->paginate(gs()->pagination);
        return view('setting.customer-type', compact('pageTitle', 'types'));
    }

    public function customerTypeStore(Request $request, $id = 0)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                Rule::unique('customer_types', 'name')->ignore($id),
            ],
        ]);

        if ($id > 0) {
            $type = CustomerType::whereId($request->id)->first();
            $message = 'Customer Type updated successfully';
            $givenStatus = isset($request->editcatstatus) ? 1 : 0;
        } else {
            $type = new CustomerType();
            $message = 'New Customer Type created successfully';
            $givenStatus = isset($request->status) ? 1 : 0;
        }

        $type->name = $request->name;
        $type->status = $givenStatus;
        $type->save();

        $notify[] = ['success', $message];
        return to_route('setting.customer.type')->withNotify($notify);
    }

    public function customerTypeDelete($id)
    {
        $type = CustomerType::find($id);
        $type->is_deleted = 1;
        $type->save();

        $notify[] = ['success', 'Customer Type successfully deleted'];
        return back()->withNotify($notify);
    }
}
